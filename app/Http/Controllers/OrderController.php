<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $details) {
            $subtotal += ($details['price'] ?? 0) * ($details['quantity'] ?? 0);
        }

        $shippingFee = (float) session()->get('shipping_fee', 0);
        $coupon = session()->get('coupon', []);
        $discount = (float) ($coupon['discount'] ?? 0);
        $couponCode = $coupon['code'] ?? null;
        $total = max(0, $subtotal + $shippingFee - $discount);

        return view('cart.checkout', compact('cart', 'subtotal', 'shippingFee', 'discount', 'couponCode', 'total'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->withInput()->with('error', 'Giỏ hàng đang trống, hãy thêm sản phẩm trước khi áp dụng mã khuyến mãi.');
        }

        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += ($details['price'] ?? 0) * ($details['quantity'] ?? 0);
        }

        $coupon = Coupon::whereRaw('LOWER(code) = ?', [strtolower(trim($request->code))])->first();

        if (! $coupon) {
            return back()->withInput()->with('error', 'Mã giảm giá không tồn tại.');
        }

        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            return back()->withInput()->with('error', 'Mã giảm giá đã hết hạn.');
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return back()->withInput()->with('error', 'Mã giảm giá đã hết lượt sử dụng.');
        }

        if ($subtotal < $coupon->min_order_value) {
            return back()->withInput()->with('error', 'Đơn hàng tối thiểu phải từ ' . number_format($coupon->min_order_value, 0, ',', '.') . ' đ.');
        }

        $discount = $coupon->type === 'fixed'
            ? min((float) $coupon->value, $subtotal)
            : ($subtotal * (float) $coupon->value / 100);

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
        ]);

        return back()->withInput()->with('success', 'Đã áp dụng mã khuyến mãi ' . $coupon->code . ' thành công!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');

        return back()->withInput()->with('success', 'Đã xóa mã khuyến mãi.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string'],
            'to_district_id' => ['required', 'integer'],
            'to_ward_code' => ['required', 'string'],
            'shipping_fee' => ['required', 'numeric'],
            'payment_method' => ['required', 'in:cod,vnpay'],
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += ($details['price'] ?? 0) * ($details['quantity'] ?? 0);
        }

        $shippingFee = (float) $request->shipping_fee;
        $coupon = session()->get('coupon', []);
        $couponCode = $coupon['code'] ?? null;
        $discountAmount = (float) ($coupon['discount'] ?? 0);
        $totalPrice = max(0, $subtotal + $shippingFee - $discountAmount);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'shipping_fee' => $shippingFee,
                'status' => 'pending',
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'coupon_code' => $couponCode,
                'discount_amount' => $discountAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $productId => $details) {
                $product = Product::findOrFail($productId);
                $quantity = (int) ($details['quantity'] ?? 0);

                if ($quantity <= 0) {
                    throw new \Exception('Số lượng sản phẩm không hợp lệ.');
                }

                if ($product->stock < $quantity) {
                    throw new \Exception('Sản phẩm "' . $product->name . '" không đủ tồn kho.');
                }

                $product->decrement('stock', $quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $details['price'],
                ]);
            }

            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('used_count');
            }

            DB::commit();

            session()->forget('cart');
            session()->forget('coupon');

            if ($request->payment_method === 'vnpay') {
                return $this->createVnpayUrl($order);
            }

            return redirect()->route('checkout.success', ['id' => $order->id])->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage())->withInput();
        }
    }

    public function success($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        return view('cart.success', compact('order'));
    }

    public function failed()
    {
        return view('cart.failed');
    }

    private function createVnpayUrl($order)
    {
        $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $vnp_Returnurl = route('payment.vnpay.return');
        $vnp_TmnCode = config('services.vnpay.tmn_code', 'YOUR_TMN_CODE');
        $vnp_HashSecret = config('services.vnpay.hash_secret', 'YOUR_HASH_SECRET');

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => (int) ($order->total_price * 100),
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => (string) $order->id,
            'vnp_OrderInfo' => 'Thanh toan don hang #' . $order->id,
            'vnp_OrderType' => 'billpayment',
            'vnp_Locale' => 'vn',
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_IpAddr' => request()->ip(),
            'vnp_CreateDate' => date('YmdHis'),
        ];

        ksort($inputData);
        $hashdata = '';
        $query = [];

        foreach ($inputData as $key => $value) {
            $query[] = urlencode($key) . '=' . urlencode($value);
            $hashdata .= ($hashdata ? '&' : '') . $key . '=' . $value;
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $query[] = 'vnp_SecureHash=' . urlencode($vnpSecureHash);

        return redirect($vnp_Url . '?' . implode('&', $query));
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('services.vnpay.hash_secret', 'YOUR_HASH_SECRET');
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = '';

        foreach ($inputData as $key => $value) {
            $hashData .= ($hashData ? '&' : '') . $key . '=' . $value;
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('checkout.failed')->with('error', 'Chữ ký bảo mật không hợp lệ.');
        }

        $order = Order::find($request->vnp_TxnRef);
        if (! $order) {
            return redirect()->route('checkout.failed')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if (($request->vnp_ResponseCode ?? '') === '00') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'transaction_id' => $request->vnp_TransactionNo ?? null,
            ]);

            return redirect()->route('checkout.success', ['id' => $order->id])->with('success', 'Thanh toán qua VNPay thành công!');
        }

        $order->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        return redirect()->route('checkout.failed')->with('error', 'Giao dịch VNPay không thành công.');
    }
}