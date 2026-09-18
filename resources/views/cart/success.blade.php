<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans">
    @php
        $isOrderSuccess = !empty($order);
    @endphp

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="{{ $isOrderSuccess ? 'bg-emerald-50 border-b border-emerald-100' : 'bg-blue-50 border-b border-blue-100' }} px-6 py-8 text-center">
                <div class="w-16 h-16 {{ $isOrderSuccess ? 'bg-emerald-600 text-white' : 'bg-blue-600 text-white' }} rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid {{ $isOrderSuccess ? 'fa-check' : 'fa-cart-shopping' }}"></i>
                </div>
                <h1 class="text-3xl font-bold {{ $isOrderSuccess ? 'text-emerald-700' : 'text-blue-700' }}">
                    {{ $isOrderSuccess ? ($order->payment_status === 'paid' || $order->payment_method === 'cod' ? 'Thanh toán thành công' : 'Đặt hàng thành công') : 'Thêm vào giỏ hàng thành công!' }}
                </h1>
                <p class="mt-2 text-slate-600">
                    {{ $isOrderSuccess ? ($order->payment_status === 'paid' || $order->payment_method === 'cod' ? 'Đơn hàng của bạn đã được xác nhận và đang được xử lý.' : 'Đơn hàng của bạn đã được ghi nhận.') : 'Sản phẩm của bạn đã được cập nhật vào giỏ hàng.' }}
                </p>
            </div>

            @if($isOrderSuccess)
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-slate-500">Mã đơn hàng</p>
                                <p class="text-lg font-bold text-slate-900">#{{ $order->id }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Trạng thái thanh toán</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-700">
                                    {{ $order->payment_status === 'paid' || $order->payment_method === 'cod' ? 'Đã thanh toán' : 'Đang xử lý' }}
                                </span>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Phương thức thanh toán</p>
                                <p class="font-semibold text-slate-900 uppercase">{{ $order->payment_method }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-slate-500">Người nhận</p>
                                <p class="font-semibold text-slate-900">{{ $order->shipping_name }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Số điện thoại</p>
                                <p class="font-semibold text-slate-900">{{ $order->shipping_phone }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Địa chỉ giao hàng</p>
                                <p class="font-semibold text-slate-900">{{ $order->shipping_address }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-200 pt-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Thông tin đơn hàng</h2>
                        <div class="space-y-3">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $item->product?->name ?? 'Sản phẩm' }}</p>
                                        <p class="text-sm text-slate-500">Số lượng: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-semibold text-slate-900">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 space-y-2 text-sm text-slate-700">
                            <div class="flex justify-between">
                                <span>Tạm tính</span>
                                <span>{{ number_format($order->total_price - $order->shipping_fee + ($order->discount_amount ?? 0), 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Phí vận chuyển</span>
                                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Giảm giá</span>
                                <span>-{{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-200">
                                <span>Tổng thanh toán</span>
                                <span>{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ url('/') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-xl text-center">
                            Quay về trang chủ
                        </a>
                        <a href="{{ url('/') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-6 rounded-xl text-center">
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            @else
                <div class="p-6 md:p-8 text-center">
                    <p class="text-slate-600 mb-6">Sản phẩm của bạn đã được cập nhật vào giỏ hàng.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-3">
                        <a href="{{ route('cart.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl text-center">
                            Xem giỏ hàng & Thanh toán
                        </a>
                        <a href="{{ url('/') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-6 rounded-xl text-center">
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>