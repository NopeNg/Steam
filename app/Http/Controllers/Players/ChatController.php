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

            $prompt = $msg;
            if ($gameInfo !== '') {
                $prompt = "Câu hỏi: {$msg}\n\nDanh sách game trong cửa hàng:\n{$gameInfo}";
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

        $games = Game::where('status', 'Active')
            ->where(function ($q) use ($msg) {
                $q->where('name', 'LIKE', "%{$msg}%")
                  ->orWhere('publisher', 'LIKE', "%{$msg}%");
            })
            ->limit(5)
            ->get();

        if ($games->isEmpty()) return '';

        $out = [];
        foreach ($games as $g) {
            $out[] = "- {$g->name} (Publisher: {$g->publisher})";
        }
        $out[] = "Lưu ý: Nếu game không có trong danh sách, cửa hàng chưa có game đó.";
        return implode("\n", $out);
    }
}