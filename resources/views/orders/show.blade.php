@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Đơn hàng</p>
            <h1 class="text-3xl font-black text-slate-900">Chi tiết đơn hàng #{{ $order->id }}</h1>
        </div>
        <a href="{{ route('orders.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl font-semibold">
            Quay lại
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Sản phẩm</h2>

            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 last:border-b-0 last:pb-0">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $item->product?->name ?? 'Sản phẩm' }}</p>
                            <p class="text-sm text-slate-500">Số lượng: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-slate-900">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</p>
                            <p class="text-xs text-slate-500">{{ number_format($item->price, 0, ',', '.') }} đ / sản phẩm</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Thông tin đơn hàng</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Người nhận</span>
                    <span class="font-semibold text-slate-800">{{ $order->shipping_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">SĐT</span>
                    <span class="font-semibold text-slate-800">{{ $order->shipping_phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Địa chỉ</span>
                    <span class="font-semibold text-slate-800 text-right max-w-[220px]">{{ $order->shipping_address }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Phương thức</span>
                    <span class="font-semibold text-slate-800 uppercase">{{ $order->payment_method }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Trạng thái</span>
                    @php
                        $statusMap = [
                            'pending' => 'Chờ xử lý',
                            'processing' => 'Đang xử lý',
                            'shipping' => 'Đang giao',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy',
                        ];
                    @endphp
                    <span class="font-semibold text-slate-800">{{ $statusMap[$order->status] ?? $order->status }}</span>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-200 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Tạm tính</span>
                    <span>{{ number_format($order->total_price - $order->shipping_fee + ($order->discount_amount ?? 0), 0, ',', '.') }} đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Phí giao hàng</span>
                    <span>{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Giảm giá</span>
                    <span>-{{ number_format($order->discount_amount ?? 0, 0, ',', '.') }} đ</span>
                </div>
                <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-200">
                    <span>Tổng thanh toán</span>
                    <span>{{ number_format($order->total_price, 0, ',', '.') }} đ</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
