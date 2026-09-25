<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Model sản phẩm của bạn
use App\Models\ChatHistory; // Model bảng lịch sử chat bạn vừa tạo
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'session_id' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        $sessionId = $request->input('session_id');

        // 1. Lưu tin nhắn của người dùng vào Database
        ChatHistory::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'message' => $userMessage,
        ]);

        // 2. Tìm kiếm sản phẩm liên quan từ Database (RAG cơ bản)
        $matchedProducts = Product::where('name', 'like', "%{$userMessage}%")
            ->orWhere('description', 'like', "%{$userMessage}%")
            ->take(3)
            ->get();

        if ($matchedProducts->isEmpty()) {
            $matchedProducts = Product::inRandomOrder()->take(3)->get();
        }

        $productContext = $matchedProducts->toJson();

        // 3. Thiết lập quy tắc (System Instruction) và thông tin sản phẩm cho AI
        $systemInstruction = "Bạn là nhân viên tư vấn bán hàng chuyên nghiệp, thân thiện. "
            . "Dưới đây là thông tin sản phẩm có sẵn trong cửa hàng:\n"
            . $productContext . "\n\n"
            . "Hãy tư vấn ngắn gọn, chính xác dựa trên danh sách này. Tuyệt đối không tự bịa ra sản phẩm không có.";

        // 4. Lấy lịch sử chat cũ của session này để Gemini KHÔNG BỊ QUÊN NGỮ CẢNH
        $contents = [];
        $histories = ChatHistory::where('session_id', $sessionId)
            ->latest()
            ->take(6) // Lấy 6 tin nhắn gần nhất
            ->get()
            ->reverse();

        foreach ($histories as $history) {
            $geminiRole = ($history->role === 'assistant') ? 'model' : 'user';
            $contents[] = [
                'role' => $geminiRole,
                'parts' => [['text' => $history->message]]
            ];
        }

        // Thêm ngữ cảnh hệ thống và câu hỏi hiện tại vào cuối mảng
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => "[Quy tắc hệ thống & Sản phẩm tham khảo]: " . $systemInstruction],
                ['text' => "Câu hỏi của khách: " . $userMessage]
            ]
        ];

        // 5. Gọi Google Gemini API
        $apiKey = env('AI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        try {
            $response = Http::post($url, [
                'contents' => $contents
            ]);

            $data = $response->json();
            $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, hệ thống tư vấn đang bận, bạn vui lòng thử lại nhé!';

        } catch (\Exception $e) {
            $botReply = "Đã xảy ra lỗi kết nối với máy chủ AI.";
        }

        // 6. Lưu câu trả lời của bot vào Database để làm bộ nhớ cho lần sau
        ChatHistory::create([
            'session_id' => $sessionId,
            'role' => 'assistant',
            'message' => $botReply,
        ]);

        return response()->json(['reply' => $botReply]);
    }
}
