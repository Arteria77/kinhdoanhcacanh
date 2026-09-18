@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow border-0 p-5 rounded-4">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle" style="width: 80px; height: 80px;">
                        <span class="fs-1">✓</span>
                    </div>
                </div>
                
                <h2 class="fw-bold text-success mb-2">Đặt Hàng Thành Công!</h2>
                <p class="text-muted mb-4">Cảm ơn bạn đã mua hàng. Mã đơn hàng của bạn là: <span class="fw-bold text-dark">#{{ $order->id }}</span></p>
                
                <div class="bg-light p-3 rounded-3 text-start mb-4">
                    <p class="mb-1"><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
                    <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                    <p class="mb-1"><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                    <p class="mb-0"><strong>Tổng thanh toán:</strong> <span class="text-danger fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} đ</span></p>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary px-4 py-2">Xem chi tiết đơn hàng</a>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4 py-2">Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection