<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $userMessage = trim($request->input('message', ''));

        if (empty($userMessage)) {
            return response()->json(['reply' => 'Bạn vui lòng nhập câu hỏi nhé!']);
        }

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => 'Chưa cấu hình GEMINI_API_KEY trong file .env!']);
        }

        // 1. Quản lý ID phiên làm việc (Session / Auth)
        $sessionId = $request->session()->getId();
        $userId = Auth::id();

        // 2. Lấy danh sách sản phẩm thực tế từ Database
        // Giả sử Model Product có quan hệ 'category' hoặc các cột name, price, description
        $products = Product::with('category')
            ->select('id', 'name', 'price', 'description', 'category_id')
            ->get();

        $productListText = "";
        foreach ($products as $product) {
            $categoryName = $product->category ? $product->category->name : 'Khác';
            $priceFormatted = number_format($product->price, 0, ',', '.') . ' VNĐ';
            $productListText .= "- ID {$product->id}: {$product->name} | Giá: {$priceFormatted} | Danh mục: {$categoryName} | Mô tả: {$product->description}\n";
        }

        // 3. Xây dựng System Context (Chỉ dẫn AI + Dữ liệu sản phẩm thực tế)
        $systemContext = "Bạn là trợ lý AI thông minh của cửa hàng cá cảnh Fashu Aqua.\n"
            . "Nhiệm vụ của bạn là tư vấn thân thiện, ngắn gọn và giới thiệu đúng các sản phẩm đang có tại cửa hàng.\n\n"
            . "DANH SÁCH SẢN PHẨM HIỆN CÓ TẠI CỬA HÀNG:\n"
            . ($productListText ?: "Hiện chưa có thông tin sản phẩm.") . "\n\n"
            . "LƯU Ý: Chỉ tư vấn và báo giá dựa trên danh sách sản phẩm trên nếu khách hàng hỏi mua cá hoặc thiết bị bể. Nếu khách hỏi về kỹ thuật nuôi, xử lý nước thì trả lời theo kiến thức thủy sinh chuẩn.";

        // 4. Đọc lịch sử hội thoại gần nhất (Lấy 6 tin nhắn mới nhất để không quá tải token)
        $historyMessages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        // 5. Chuẩn bị định dạng payload cho Gemini API
        $contents = [];

        // Đưa system context vào tin nhắn user đầu tiên
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => "[System Context]\n" . $systemContext]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => "Đã hiểu! Tôi sẵn sàng tư vấn khách hàng dựa trên danh sách sản phẩm của Fashu Aqua."]]
        ];

        // Nối lịch sử trò chuyện cũ
        foreach ($historyMessages as $msg) {
            $contents[] = [
                'role' => $msg->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg->message]]
            ];
        }

        // Nối câu hỏi mới của khách hàng
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key=" . trim($apiKey);

            $response = Http::withoutVerifying()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => $contents
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi chưa hiểu ý bạn.';

                // 6. Lưu cuộc hội thoại vào Database
                ChatMessage::create([
                    'session_id' => $sessionId,
                    'user_id'    => $userId,
                    'role'       => 'user',
                    'message'    => $userMessage,
                ]);

                ChatMessage::create([
                    'session_id' => $sessionId,
                    'user_id'    => $userId,
                    'role'       => 'model',
                    'message'    => $reply,
                ]);

                return response()->json(['reply' => $reply]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json([
                'reply' => 'Lỗi kết nối Gemini API (HTTP ' . $response->status() . '): ' . ($response->json()['error']['message'] ?? 'Hệ thống bận.')
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Có lỗi ngoại lệ: ' . $e->getMessage()]);
        }
    }
}