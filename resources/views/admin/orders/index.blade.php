@extends('layouts.admin') {{-- Thay đổi layout admin tùy theo project của bạn --}}

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Quản lý Đơn hàng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form lọc trạng thái -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phí vận chuyển</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                            <td>{{ number_format($order->total_price ?? $order->grand_total ?? 0, 0, ',', '.') }} đ</td>
                            <td>{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ</td>
                            <td>
                                @php
                                    $badges = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipping' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $labels = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipping' => 'Đang giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }}">
                                    {{ $labels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">Chi tiết</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection