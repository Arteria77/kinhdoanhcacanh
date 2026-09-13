@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Thông tin đơn hàng & khách hàng -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Thông tin người nhận</div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> {{ $order->name ?? $order->user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $order->email ?? $order->user->email ?? 'N/A' }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone ?? 'N/A' }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Cập nhật trạng thái đơn hàng -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">Trạng thái đơn hàng</div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3">
                            <label>Trạng thái hiện tại:</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Cập nhật trạng thái</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm trong đơn hàng -->
    <div class="card">
        <div class="card-header bg-dark text-white">Sản phẩm đặt mua</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td style="width: 80px;">
                                @if(!empty($item->product->image))
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>{{ $item->product->name ?? 'Sản phẩm không còn tồn tại' }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tổng kết tiền -->
            <div class="text-end mt-3">
                <p>Phí vận chuyển: <strong>{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ</strong></p>
                <h4>Tổng thanh toán: <span class="text-danger">{{ number_format($order->total_price ?? $order->grand_total ?? 0, 0, ',', '.') }} đ</span></h4>
            </div>
        </div>
    </div>
</div>
@endsection