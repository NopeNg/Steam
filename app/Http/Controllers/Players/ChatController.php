<?php
namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $msg = trim($request->input('message', ''));
            if ($msg === '') {
                return response()->json(['candidates' => [['content' => ['parts' => [['text' => 'Vui lòng nhập câu hỏi.']]]]]]);
            }

            $key = env('GEMINI_API_KEY');
            if (!$key) {
                return response()->json(['candidates' => [['content' => ['parts' => [['text' => 'Chưa cấu hình GEMINI_API_KEY.']]]]]]);
            }

            $gameInfo = '';
            try {
                $gameInfo = $this->getGameContext($msg);
            } catch (\Throwable $e) {
                Log::warning('[Chat] game search error: ' . $e->getMessage());
            }

            // System prompt: Luôn chỉ sử dụng dữ liệu từ DB
            $systemPrompt = "Bạn là trợ lý AI thân thiện và tích cực của cửa hàng GameKey - cửa hàng bán key game bản quyền Steam. "
                . "QUY TẮC BẮT BUỘC:\n"
                . "1. CHỈ trả lời dựa trên danh sách game được cung cấp bên dưới.\n"
                . "2. Khi user hỏi về GIÁ của game: Nếu game có trong danh sách, hãy nói rõ giá là MIỄN PHÍ nếu price = 0, hoặc đưa ra giá chính xác từ hệ thống.\n"
                . "3. Nếu user hỏi về game KHÔNG có trong danh sách, hãy trả lời tích cực, thân thiện. KHÔNG dùng từ 'rất tiếc' hoặc 'xin lỗi'.\n"
                . "4. Nếu game không có trong danh sách, gợi ý user tìm game khác trong cửa hàng hoặc kiểm tra lại sau.\n"
                . "5. TUYỆT ĐỐI không bịa đặt tên game, nhà phát hành, hoặc thông tin game không có trong danh sách.\n"
                . "6. Trả lời ngắn gọn, thân thiện, lạc quan bằng tiếng Việt.\n"
                . "7. Với câu hỏi không liên quan đến game, có thể trả lời chung về cửa hàng một cách nhiệt tình.\n\n"
                . "LƯU Ý QUAN TRỌNG VỀ GIÁ:\n"
                . "- Nếu game trong danh sách là MIỄN PHÍ (Free-to-play), hãy thông báo rõ ràng và tích cực.\n"
                . "- Không nói 'để biết giá chính xác hãy ghé thăm cửa hàng' khi đã biết thông tin giá.\n";

            $prompt = $msg;
            if ($gameInfo !== '') {
                $prompt = $systemPrompt . "Danh sách game HIỆN CÓ trong cửa hàng:\n{$gameInfo}\n\n"
                    . "Câu hỏi của người dùng: {$msg}\n"
                    . "Trả lời (CHỈ dựa trên danh sách trên):";
            } else {
                $prompt = $systemPrompt . "Câu hỏi của người dùng: {$msg}\n"
                    . "Lưu ý: Hiện không tìm thấy game nào phù hợp trong database. Hãy thông báo người dùng tìm kiếm với tên khác.";
            }

            $body = json_encode([
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
            $fullUrl = $url . '?key=' . urlencode($key);

            try {
                $res = Http::withBody($body, 'application/json')
                    ->timeout(20)
                    ->withOptions([
                        'verify' => false,
                        'curl' => [CURLOPT_SSL_VERIFYHOST => false],
                    ])
                    ->post($fullUrl);

                if ($res->successful()) {
                    $data = $res->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi.';
                    return response()->json(['candidates' => [['content' => ['parts' => [['text' => $text]]]]]]);
                }

                $errBody = mb_substr($res->body(), 0, 500);
                Log::error('[Chat] Gemini HTTP ' . $res->status() . ': ' . $errBody);
                return response()->json(['candidates' => [['content' => ['parts' => [['text' => 'AI trả lỗi (' . $res->status() . '): ' . $errBody]]]]]]);
            } catch (\Throwable $e) {
                Log::error('[Chat] Gemini request exception: ' . $e->getMessage());
                return response()->json(['candidates' => [['content' => ['parts' => [['text' => 'Không thể kết nối AI: ' . $e->getMessage()]]]]]]);
            }
            
            // Không bao giờ chạy đến đây
            return response()->json(['candidates' => [['content' => ['parts' => [['text' => 'Lỗi không xác định.']]]]]]);

        } catch (\Throwable $e) {
            Log::error('[Chat] fatal: ' . $e->getMessage());
            return response()->json(['candidates' => [['content' => ['parts' => [['text' => 'Lỗi: ' . $e->getMessage()]]]]]]);
        }
    }

    private function getGameContext(string $msg): string
    {
        $msg = mb_strtolower(trim($msg));
        if ($msg === '') return '';

        // Lấy TẤT CẢ game active với thông tin giá (giới hạn 20 để không vượt token limit)
        $games = Game::where('status', 'Active')
            ->with(['versions' => function ($q) {
                $q->select('game_id', 'price', 'discount_price')->orderBy('price', 'asc')->limit(1);
            }])
            ->limit(20)
            ->get(['name', 'publisher', 'description']);

        if ($games->isEmpty()) return '';

        $out = [];
        foreach ($games as $g) {
            $price = 'Không rõ';
            if ($g->versions->isNotEmpty()) {
                $v = $g->versions->first();
                if ($v->price == 0) {
                    $price = 'MIỄN PHÍ (Free-to-play)';
                } elseif ($v->discount_price && $v->discount_price < $v->price) {
                    $price = number_format($v->discount_price, 0, ',', '.') . 'đ (giảm từ ' . number_format($v->price, 0, ',', '.') . 'đ)';
                } else {
                    $price = number_format($v->price, 0, ',', '.') . 'đ';
                }
            }
            $out[] = "- {$g->name} (Publisher: {$g->publisher}) - Giá: {$price}";
        }
        $out[] = "\nLưu ý: Chỉ trả lời về các game trong danh sách trên. Cửa hàng KHÔNG có game ngoài danh sách.";
        $out[] = "QUAN TRỌNG VỀ GIÁ: Nếu game có giá 0đ hoặc MIỄN PHÍ, hãy thông báo rõ ràng và tích cực cho người dùng.";
        return implode("\n", $out);
    }
}