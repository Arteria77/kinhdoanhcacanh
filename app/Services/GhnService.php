<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GhnService
{
    /**
     * Lấy cấu hình GHN API
     */
    public static function getConfig(): array
    {
        return [
            'token' => config('services.ghn.token', 'eea1eb4a-aa85-11f1-a973-aee5264794df'),
            'shop_id' => (int) config('services.ghn.shop_id', 217505),
            'api_url' => rtrim(config('services.ghn.api_url', 'https://dev-online-gateway.ghn.vn/shiip/public-api'), '/'),
        ];
    }

    /**
     * Tạo mã vận đơn trên GHN và tự động cập nhật trạng thái đơn hàng sang "shipping" (Chờ giao hàng)
     */
    public static function createShippingOrder(Order $order): array
    {
        // 1. Nếu đơn hàng đã có mã vận đơn GHN rồi thì không tạo lại
        if (!empty($order->ghn_order_code)) {
            return [
                'success' => true,
                'order_code' => $order->ghn_order_code,
                'message' => 'Đơn hàng này đã có mã vận đơn GHN: ' . $order->ghn_order_code,
            ];
        }

        $config = self::getConfig();

        // 2. Chuẩn bị danh sách sản phẩm gửi GHN
        $items = [];
        $order->loadMissing('orderItems.product');

        foreach ($order->orderItems as $item) {
            $items[] = [
                'name' => $item->product->name ?? 'Cá cảnh thủy sinh',
                'code' => 'SP-' . ($item->product_id ?? 1),
                'quantity' => (int) ($item->quantity ?? 1),
                'price' => (int) round($item->price ?? 10000),
                'weight' => 200, // 200 gram mỗi cá/phụ kiện
            ];
        }

        if (empty($items)) {
            $items[] = [
                'name' => 'Cá cảnh thủy sinh #' . $order->id,
                'code' => 'DH-' . $order->id,
                'quantity' => 1,
                'price' => (int) round($order->total_price),
                'weight' => 500,
            ];
        }

        // 3. Xử lý địa chỉ & người nhận (fallback an toàn nếu đơn cũ chưa lưu ward/district)
        $toDistrictId = (int) ($order->to_district_id ?: 1450); // Fallback: Quận Cầu Giấy
        $toWardCode = (string) ($order->to_ward_code ?: '20806'); // Fallback: Dịch Vọng Hậu

        // Tiền thu hộ COD (nếu đã thanh toán online SePay/VNPay thì cod = 0)
        $codAmount = ($order->payment_status === 'paid') ? 0 : (int) round($order->total_price);

        // 4. Payload chuẩn của GHN API v2
        $payload = [
            'payment_type_id' => 1, // 1: Người gửi trả cước ship (Shop trả cước)
            'note' => 'Đơn hàng cá cảnh #' . $order->id . ' - Giao cẩn thận tránh sốc nước',
            'required_note' => 'CHOXEMHANGKHONGTHU',
            'to_name' => $order->shipping_name ?: ($order->user->name ?? 'Khách hàng Fashu'),
            'to_phone' => $order->shipping_phone ?: '0326826648',
            'to_address' => $order->shipping_address ?: 'Việt Nam',
            'to_district_id' => $toDistrictId,
            'to_ward_code' => $toWardCode,
            'cod_amount' => $codAmount,
            'content' => 'Đơn hàng cá cảnh Fashu #' . $order->id,
            'weight' => max(500, count($items) * 250),
            'length' => 20,
            'width' => 15,
            'height' => 15,
            'service_type_id' => 2, // Chuẩn E-Commerce
            'items' => $items,
        ];

        try {
            Log::info('GHN: Bắt đầu gọi API tạo vận đơn cho đơn #' . $order->id, $payload);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $config['token'],
                'ShopId' => $config['shop_id'],
            ])->timeout(15)->post($config['api_url'] . '/v2/shipping-order/create', $payload);

            $data = $response->json();

            if ($response->successful() && isset($data['code']) && $data['code'] == 200) {
                $ghnOrderCode = $data['data']['order_code'] ?? null;

                if ($ghnOrderCode) {
                    // Cập nhật mã vận đơn và chuyển trạng thái sang "shipping" (Chờ giao hàng)
                    $order->update([
                        'ghn_order_code' => $ghnOrderCode,
                        'status' => 'shipping',
                    ]);

                    Log::info("GHN: Tạo thành công vận đơn {$ghnOrderCode} cho đơn hàng #{$order->id}");

                    return [
                        'success' => true,
                        'order_code' => $ghnOrderCode,
                        'data' => $data['data'],
                        'message' => 'Tạo mã vận đơn GHN thành công: ' . $ghnOrderCode,
                    ];
                }
            }

            $errorMessage = $data['message_display'] ?? $data['message'] ?? 'Lỗi không xác định từ GHN';
            Log::error("GHN: Lỗi tạo vận đơn cho đơn #{$order->id}: " . $errorMessage, (array) $data);

            return [
                'success' => false,
                'message' => 'GHN: ' . $errorMessage,
            ];

        } catch (\Exception $e) {
            Log::error("GHN Exception khi tạo vận đơn #{$order->id}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi kết nối GHN: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sinh link tra cứu mã vận đơn GHN
     */
    public static function getTrackingUrl(?string $orderCode): string
    {
        if (empty($orderCode)) {
            return '#';
        }
        return 'https://tracking.ghn.vn/?order_code=' . urlencode($orderCode);
    }
}
