<?php
namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    private $models = [
        'gemini-2.0-flash',
        'gemini-2.5-flash',
        'gemini-1.5-flash',
        'gemini-2.0-flash-lite',
        'gemini-2.5-flash-lite',
        'gemini-1.5-pro',
        'gemini-2.0-pro',
        'gemini-2.5-pro',
    ];
    private $systemPrompt = 'Bạn là Bee, một nhân viên tư vấn game tại cửa hàng SteamKey.

Giọng điệu: Thân thiện, chi tiết và hữu ích. 
Đi thẳng vào vấn đề của khách hàng, 
tuyệt đối không dùng các câu chào hỏi rườm rà hay lặp lại lời chào đầu tiên.
Quy tắc tư vấn:
1. Tập trung vào yêu cầu của khách hàng và chỉ sử 
dụng dữ liệu game có sẵn trong cửa hàng để tư vấn.
2. Không được phép trả lời các chủ đề ngoài game. 
Nếu khách hàng hỏi vấn đề khác, hãy từ chối ngắn gọn, l
ịch sự và nhắc lại rằng bạn chỉ hỗ trợ tư vấn game tại cửa hàng.
3. Nếu khách hàng hỏi về một tựa game cụ thể: 
Tập trung phân tích game đó. Nếu cửa hàng không có sẵn game đó, 
chỉ giới thiệu thêm tối đa 1-2 game có nội dung/thể loại liên quan nhất đang có sẵn.
 Hạn chế việc liệt kê lan man.
 4. Không được phép phản hồi quá dài trong một lần gửi tránh để khách hàng phải đọc quá nhiều chữ .';

    public function chat(Request $request)
    {
        $msg = $request->input('message', '');
        if (!$msg)
            return $this->ok('Vui lòng nhập câu hỏi.');

        $key = env('GEMINI_API_KEY');
        $url = 'https://generativelanguage.googleapis.com/v1beta/models';

        // === TÌM KIẾM GAME TRONG DATABASE ===
        $gameContext = $this->searchGames($msg);

        // Tạo nội dung gửi AI
        $fullMessage = $msg;
        if ($gameContext) {
            $fullMessage = "Câu hỏi khách: {$msg}\n\nDữ liệu game trong cửa hàng:\n{$gameContext}";
        }

        $payload = ['contents' => [['parts' => [['text' => $fullMessage]]]]];

        // Thêm system instruction nếu có
        $prompt = trim($this->systemPrompt);
        if ($prompt) {
            $payload['system_instruction'] = ['parts' => [['text' => $prompt]]];
        }

        // Shuffle để load balance
        $models = $this->models;
        shuffle($models);

        foreach ($models as $model) {
            try {
                $res = Http::withoutVerifying()
                    ->timeout(15)
                    ->post("{$url}/{$model}:generateContent?key={$key}", $payload);

                if ($res->successful() && isset($res->json()['candidates'])) {
                    return response()->json($res->json());
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $this->ok('Tất cả model đều bận. Vui lòng thử lại sau.');
    }

    /**
     * Tìm game theo từ khóa trong câu hỏi khách
     */
    private function searchGames(string $message): string
    {
        $games = Game::with([
            'versions' => function ($q) {
                $q->select('id', 'game_id', 'version_name', 'price', 'discount_price');
            }
        ])
            ->where('status', 'Active')
            ->where(function ($q) use ($message) {
                $words = explode(' ', $message);
                foreach ($words as $word) {
                    $word = trim($word);
                    if (strlen($word) < 2)
                        continue;
                    $q->orWhere('name', 'LIKE', "%{$word}%");
                    $q->orWhere('publisher', 'LIKE', "%{$word}%");
                    $q->orWhere('developer', 'LIKE', "%{$word}%");
                    $q->orWhere('description', 'LIKE', "%{$word}%");
                }
            })
            ->limit(5)
            ->get();

        if ($games->isEmpty())
            return '';

        $lines = [];
        foreach ($games as $g) {
            $prices = $g->versions->map(function ($v) {
                $price = $v->discount_price ? number_format($v->discount_price) . 'đ' : number_format($v->price) . 'đ';
                return "  + {$v->version_name}: {$price}";
            })->toArray();

            $lines[] = "- {$g->name} (Nhà phát hành: {$g->publisher}, Nhà phát triển: {$g->developer})";
            if (!empty($prices)) {
                $lines[] = "  Giá bán:";
                $lines = array_merge($lines, $prices);
            }
        }

        return implode("\n", $lines);
    }

    private function ok($text)
    {
        return response()->json([
            'candidates' => [['content' => ['parts' => [['text' => $text]]]]]
        ]);
    }
}