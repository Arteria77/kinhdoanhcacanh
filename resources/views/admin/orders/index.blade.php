<<<<<<< HEAD
@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng & Vận chuyển GHN')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>📋</span> Quản Lý Đơn Hàng & Vận Đơn GHN
            </h1>
            <p class="text-sm text-slate-500 mt-1">Quản lý trạng thái xử lý, đối soát thanh toán và kết nối tự động với Giao Hàng Nhanh (GHN).</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                <span>🚚</span> GHN Sandbox Active
            </span>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <!-- Tổng đơn -->
        <a href="{{ route('admin.orders.index') }}" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-indigo-400 transition">
            <span class="text-xs font-bold text-slate-400 uppercase">Tất cả đơn</span>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
        </a>

        <!-- Chờ xử lý -->
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-amber-400 transition">
            <span class="text-xs font-bold text-amber-600 uppercase">Chờ xử lý</span>
            <p class="text-xl sm:text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
        </a>

        <!-- Đang xử lý -->
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-blue-400 transition">
            <span class="text-xs font-bold text-blue-600 uppercase">Đóng gói</span>
            <p class="text-xl sm:text-2xl font-extrabold text-blue-600 mt-1">{{ $stats['processing'] }}</p>
        </a>

        <!-- Chờ GHN giao -->
        <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200 shadow-xs hover:border-indigo-500 transition">
            <span class="text-xs font-bold text-indigo-700 uppercase">Chờ GHN giao</span>
            <p class="text-xl sm:text-2xl font-extrabold text-indigo-700 mt-1">{{ $stats['shipping'] }}</p>
        </a>

        <!-- Hoàn thành -->
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-emerald-400 transition">
            <span class="text-xs font-bold text-emerald-600 uppercase">Hoàn thành</span>
            <p class="text-xl sm:text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['completed'] }}</p>
        </a>

        <!-- Doanh thu -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white shadow-xs">
            <span class="text-xs font-bold text-slate-300 uppercase">Doanh thu</span>
            <p class="text-lg sm:text-xl font-extrabold text-emerald-400 mt-1 truncate">{{ number_format($stats['revenue'], 0, ',', '.') }} đ</p>
        </div>
    </div>

    <!-- Bộ Lọc & Tìm Kiếm -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Tìm kiếm từ khóa -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tìm kiếm</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" 
                       placeholder="Mã đơn #, Tên khách, SĐT, Mã vận đơn GHN..."
                       class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Trạng thái đơn -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Trạng thái đơn</label>
                <select name="status" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý / Đóng gói</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Chờ giao hàng (GHN)</option>
=======
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-600">📋 Quản lý đơn hàng</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:underline">Quản lý sản phẩm</a>
            <a href="/" class="text-gray-600 hover:underline">Trang chủ</a>
        </div>
    </div>

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
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
<<<<<<< HEAD

            <!-- Trạng thái thanh toán -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Thanh toán</label>
                <select name="payment_status" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Tất cả thanh toán --</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                </select>
            </div>

            <!-- Nút Lọc & Xóa lọc -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                    Lọc đơn
                </button>
                @if(request()->hasAny(['keyword', 'status', 'payment_status', 'payment_method']))
                    <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 text-sm font-semibold rounded-xl text-slate-500 hover:bg-slate-100 border border-slate-300 transition" title="Xóa bộ lọc">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bảng Danh Sách Đơn Hàng -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase border-b border-slate-200">
                        <th class="p-4">Mã đơn</th>
                        <th class="p-4">Khách hàng & Người nhận</th>
                        <th class="p-4">Cổng thanh toán</th>
                        <th class="p-4">Vận đơn GHN</th>
                        <th class="p-4">Tổng thanh toán</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4">Thời gian</th>
                        <th class="p-4 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/80 transition">
                        <!-- Mã đơn -->
                        <td class="p-4 font-extrabold text-slate-900">
                            #{{ $order->id }}
                        </td>

                        <!-- Người nhận -->
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $order->shipping_name ?: ($order->user->name ?? 'Khách vãng lai') }}</p>
                            <p class="text-xs text-slate-500 font-mono">{{ $order->shipping_phone }}</p>
                            <p class="text-xs text-slate-400 truncate max-w-xs">{{ $order->shipping_address }}</p>
                        </td>

                        <!-- Thanh toán -->
                        <td class="p-4">
                            <div class="space-y-1">
                                <span class="inline-block text-xs font-bold uppercase text-slate-700">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                                <div>
                                    @if($order->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span>✓</span> Đã thanh toán
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span>⏳</span> Chưa thanh toán
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Vận đơn GHN -->
                        <td class="p-4">
                            @if($order->ghn_order_code)
                                <div class="space-y-1">
                                    <a href="https://tracking.ghn.vn/?order_code={{ $order->ghn_order_code }}" target="_blank" 
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition shadow-2xs" title="Xem hành trình bưu phẩm trên GHN">
                                        <span>🚚</span> {{ $order->ghn_order_code }}
                                    </a>
                                    <span class="block text-[10px] text-slate-400">Đã đẩy đơn GHN</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400 italic">Chưa tạo</span>
                                    <form action="{{ route('admin.orders.createGhn', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Tạo mã vận đơn trên GHN cho đơn hàng #{{ $order->id }}?')">
                                        @csrf
                                        <button type="submit" class="text-[11px] px-2 py-0.5 rounded bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold border border-indigo-200">
                                            + Tạo GHN
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>

                        <!-- Tổng tiền & ship -->
                        <td class="p-4">
                            <p class="font-extrabold text-blue-600">
                                {{ number_format($order->total_price ?? 0, 0, ',', '.') }} đ
                            </p>
                            <span class="text-xs text-slate-400">
                                (Ship: {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ)
                            </span>
                        </td>

                        <!-- Trạng thái đơn -->
                        <td class="p-4">
                            @php
                                $badges = [
                                    'pending' => ['Chờ xử lý', 'bg-amber-100 text-amber-800 border-amber-200'],
                                    'processing' => ['Đang xử lý', 'bg-blue-100 text-blue-800 border-blue-200'],
                                    'shipping' => ['Chờ giao hàng (GHN)', 'bg-indigo-100 text-indigo-800 border-indigo-200 font-bold'],
                                    'completed' => ['Hoàn thành', 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                                    'cancelled' => ['Đã hủy', 'bg-rose-100 text-rose-800 border-rose-200'],
                                ];
                                $badge = $badges[$order->status] ?? [$order->status, 'bg-slate-100 text-slate-700 border-slate-200'];
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badge[1] }}">
                                {{ $badge[0] }}
                            </span>
                        </td>

                        <!-- Ngày đặt -->
                        <td class="p-4 text-xs text-slate-500 whitespace-nowrap">
                            {{ $order->created_at->format('d/m/Y') }}<br>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $order->created_at->format('H:i') }}</span>
                        </td>

                        <!-- Thao tác -->
                        <td class="p-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-700 transition border border-slate-200">
                                Chi tiết &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">
                            Không tìm thấy đơn hàng nào phù hợp với điều kiện tìm kiếm.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        @if($orders->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
=======
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
</body>
</html>
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
