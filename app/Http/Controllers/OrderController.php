<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Hiển thị trang thanh toán (checkout)
    public function checkout()
    {
        $cart = session()->get('cart', []);
        return view('cart.checkout', compact('cart'));
    }

    // Lưu đơn hàng mới khi khách bấm "Đặt hàng ngay"
    public function store(Request $request)
    {
        // Validate dữ liệu gửi lên từ form thanh toán
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'to_district_id' => 'required|integer',
            'to_ward_code' => 'required|string',
            'shipping_fee' => 'required|numeric',
        ]);

        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Tính tổng tiền hàng
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $shippingFee = (float) $request->input('shipping_fee', 0);
        $totalPrice = $subtotal + $shippingFee;

        // Sử dụng Database Transaction để đảm bảo tính toàn vẹn dữ liệu
        try {
            DB::beginTransaction();

            // 1. Tạo bản ghi đơn hàng chính
            $order = Order::create([
                'user_id' => auth()->id(), // Yêu cầu khách hàng phải đăng nhập
                'total_price' => $totalPrice, // Tiền hàng + Phí ship GHN
                'shipping_fee' => $shippingFee, // Phí ship lấy từ GHN
                'status' => 'pending', // Trạng thái đơn: chờ xử lý
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'to_district_id' => $request->to_district_id,
                'to_ward_code' => $request->to_ward_code,
            ]);

            // 2. Lưu từng sản phẩm trong giỏ vào bảng chi tiết đơn hàng
            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            DB::commit();

            // 3. Xóa giỏ hàng sau khi đặt thành công
            session()->forget('cart');

            // Đã sửa lại đúng tên route 'cart.success' khớp với file web.php của bạn
            return redirect()->route('cart.success')->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage())->withInput();
        }
    }

    // Trang thông báo đặt hàng thành công (nếu bạn muốn chuyển hẳn hàm này sang OrderController)
    public function success()
    {
        return view('cart.order-success');
    }
}