@extends('layouts.app') <!-- Hoặc layout chính của bạn -->

@section('content')
<div class="container py-5 text-center">
    <div class="card shadow p-5 mx-auto" style="max-width: 600px;">
        <div class="mb-4">
            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
        </div>
        <h2 class="fw-bold text-danger mb-2">Thanh Toán Thất Bại!</h2>
        <p class="text-muted mb-4">{{ session('error') ?? 'Đã có lỗi xảy ra trong quá trình thanh toán hoặc đặt hàng. Vui lòng thử lại.' }}</p>
        
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('checkout') }}" class="btn btn-danger">Thử lại thanh toán</a>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Về trang chủ</a>
        </div>
    </div>
</div>
@endsection