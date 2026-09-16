@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Tài khoản</p>
            <h1 class="text-3xl font-black text-slate-900">Đơn hàng của tôi</h1>
        </div>
        <a href="{{ url('/') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl font-semibold">
            Tiếp tục mua sắm
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Mã đơn</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Tổng tiền</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Thanh toán</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Ngày đặt</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($orders as $order)
                        <tr class="align-top">
                            <td class="px-4 py-4 font-bold text-slate-800">#{{ $order->id }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                @foreach($order->orderItems->take(2) as $item)
                                    <div>{{ $item->product?->name ?? 'Sản phẩm' }} x{{ $item->quantity }}</div>
                                @endforeach
                                @if($order->orderItems->count() > 2)
                                    <div class="text-xs text-slate-500">+{{ $order->orderItems->count() - 2 }} sản phẩm khác</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-4 text-sm text-slate-600 uppercase">{{ $order->payment_method }}</td>
                            <td class="px-4 py-4">
                                @php
                                    $statusMap = [
                                        'pending' => ['label' => 'Chờ xử lý', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'processing' => ['label' => 'Đang xử lý', 'class' => 'bg-blue-100 text-blue-700'],
                                        'shipping' => ['label' => 'Đang giao', 'class' => 'bg-indigo-100 text-indigo-700'],
                                        'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-emerald-100 text-emerald-700'],
                                        'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $status = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-slate-100 text-slate-700'];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-3 py-2 rounded-lg">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Bạn chưa có đơn hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
