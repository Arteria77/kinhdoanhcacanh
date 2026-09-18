<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Hiển thị trang thanh toán kèm tổng tiền, giảm giá
    public function index()
    {
        // Lấy tổng tiền hàng từ Session giỏ hàng của bạn (Ví dụ dùng session giỏ hàng)
        $subtotal = session()->get('cart_subtotal', 1000000); // Mẫu giả định 1 triệu VNĐ
        $discount = 0;
        $couponCode = null;

        if (session()->has('coupon')) {
            $couponData = session()->get('coupon');
            $couponCode = $couponData['code'];
            $discount = $couponData['discount'];
        }

        $total = max(0, $subtotal - $discount);

        return view('checkout', compact('subtotal', 'discount', 'total', 'couponCode'));
    }

    // Áp dụng mã giảm giá
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            return back()->with('error', 'Mã giảm giá đã hết hạn.');
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return back()->with('error', 'Mã giảm giá đã hết lượt sử dụng.');
        }

        $subtotal = session()->get('cart_subtotal', 1000000); // Lấy subtotal giỏ hàng thực tế của bạn

        if ($subtotal < $coupon->min_order_value) {
            return back()->with('error', 'Đơn hàng tối thiểu phải từ ' . number_format($coupon->min_order_value) . ' VNĐ.');
        }

        $discount = ($coupon->type == 'fixed') ? $coupon->value : ($subtotal * $coupon->value / 100);

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
        ]);

        return back()->with('success', 'Đã áp dụng mã giảm giá thành công!');
    }

    // Gỡ mã giảm giá
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Đã hủy mã giảm giá.');
    }

    // Xử lý Đặt hàng (Hỗ trợ COD hoặc VNPay)
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|in:cod,vnpay',
        ]);

        $subtotal = session()->get('cart_subtotal', 1000000);
        $discount = session()->has('coupon') ? session()->get('coupon')['discount'] : 0;
        $couponCode = session()->has('coupon') ? session()->get('coupon')['code'] : null;
        $total = max(0, $subtotal - $discount);

        DB::beginTransaction();
        try {
            // 1. Tạo bản ghi Đơn hàng (ĐÃ SỬA LỖI: Dùng Auth::id() thay vì auth()->id())
            $order = Order::create([
                'user_id' => Auth::id() ?? null,
                'total_price' => $total,
                'status' => 'pending',
                'coupon_code' => $couponCode,
                'discount_amount' => $discount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                // Lưu thêm các trường thông tin nhận hàng khác nếu có ở đây...
            ]);

            // Nếu có dùng coupon, tăng số lần dùng lên 1
            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('used_count');
            }

            DB::commit();

            // Xóa session giỏ hàng và coupon tạm
            session()->forget('coupon');

            // 2. Phân loại theo phương thức thanh toán
            if ($request->payment_method == 'vnpay') {
                return $this->createVNPayUrl($order);
            }

            // Mặc định COD (Thanh toán khi nhận hàng)
            return redirect()->route('home')->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Tạo URL chuyển hướng sang VNPay
    private function createVNPayUrl($order)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('payment.vnpay.return');
        $vnp_TmnCode = "YOUR_TMN_CODE"; // Thay mã Terminal của bạn
        $vnp_HashSecret = "YOUR_HASH_SECRET"; // Thay chuỗi bí mật của bạn

        $vnp_TxnRef = $order->id; // Mã đơn hàng làm mã giao dịch
        $vnp_OrderInfo = 'Thanh toán đơn hàng cá cảnh #' . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total_price * 100; // VNPay yêu cầu nhân 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.10.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    // Xử lý kết quả trả về từ VNPay
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "YOUR_HASH_SECRET"; // Chuỗi bí mật VNPay của bạn
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            $orderId = $request->vnp_TxnRef;
            $order = Order::find($orderId);

            if ($order) {
                if ($request->vnp_ResponseCode == '00') {
                    // Thanh toán thành công
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'transaction_id' => $request->vnp_TransactionNo ?? null
                    ]);
                    return redirect()->route('home')->with('success', 'Thanh toán đơn hàng cá cảnh qua VNPay thành công!');
                } else {
                    // Thanh toán thất bại
                    $order->update(['payment_status' => 'failed']);
                    return redirect()->route('checkout.index')->with('error', 'Giao dịch VNPay không thành công.');
                }
            }
        }

        return redirect()->route('checkout.index')->with('error', 'Chữ ký bảo mật không hợp lệ.');
    }
}