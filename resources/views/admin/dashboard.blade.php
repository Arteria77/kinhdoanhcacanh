@extends('layouts.admin')

@section('title', 'Báo cáo Doanh thu & Tổng quan')

@section('content')
<div class="space-y-6">
    <!-- Top Title & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>📊</span> Báo Cáo Doanh Thu & Kinh Doanh
            </h1>
            <p class="text-sm text-slate-500 mt-1">Theo dõi doanh thu thực tế, tiến độ vận đơn GHN và danh mục các loài cá cảnh.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/30 transition">
                <span>📋</span> Xem tất cả đơn hàng
            </a>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition">
                <span>+</span> Thêm cá cảnh mới
            </a>
        </div>
    </div>

    <!-- 4 Khối Thống Kê Tài Chính Hàng Đầu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Tổng doanh thu -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/15 relative overflow-hidden">
            <div class="absolute -right-3 -bottom-3 text-7xl opacity-10">💰</div>
            <p class="text-xs font-bold uppercase tracking-wider text-blue-100">Tổng doanh thu thực tế</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold mt-2 tracking-tight">
                {{ number_format($totalRevenue, 0, ',', '.') }} <span class="text-base font-normal">đ</span>
            </h3>
            <div class="mt-3 flex items-center text-xs text-blue-100 bg-white/10 w-fit px-2.5 py-1 rounded-lg">
                <span>✓ Đơn đã thanh toán & hoàn thành</span>
            </div>
        </div>

        <!-- Doanh thu hôm nay -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Doanh thu hôm nay</p>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">⚡</span>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-2">
                {{ number_format($todayRevenue, 0, ',', '.') }} <span class="text-sm font-normal text-slate-500">đ</span>
            </h3>
            <p class="text-xs text-slate-500 mt-3">Ngày {{ date('d/m/Y') }}</p>
        </div>

        <!-- Doanh thu tháng này -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Doanh thu tháng {{ date('m/Y') }}</p>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">📅</span>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-2">
                {{ number_format($thisMonthRevenue, 0, ',', '.') }} <span class="text-sm font-normal text-slate-500">đ</span>
            </h3>
            <p class="text-xs text-slate-500 mt-3">Tháng hiện tại</p>
        </div>

        <!-- Vận chuyển GHN & Chờ giao -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Chờ giao hàng (GHN)</p>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">🚚</span>
            </div>
            <h3 class="text-2xl font-extrabold text-amber-600 mt-2">
                {{ $orderCounts['shipping'] }} <span class="text-sm font-normal text-slate-500">đơn hàng</span>
            </h3>
            <p class="text-xs text-slate-500 mt-3">Đã có mã vận đơn GHN</p>
        </div>
    </div>

    <!-- Phân Tích Kênh Thu Tiền & Trạng Thái Đơn Hàng -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Doanh thu theo cổng thanh toán -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span>💳</span> Doanh thu theo Cổng thanh toán
            </h2>
            <div class="space-y-4">
                <!-- SePay -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                            QR
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-800">VietQR (SePay)</p>
                            <span class="text-xs text-slate-500">Chuyển khoản tức thì</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-slate-900 text-sm">{{ number_format($revenueByMethod['sepay'], 0, ',', '.') }} đ</span>
                    </div>
                </div>

                <!-- VNPay -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                            VN
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-800">Cổng VNPay</p>
                            <span class="text-xs text-slate-500">Thẻ ATM / Ví điện tử</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-slate-900 text-sm">{{ number_format($revenueByMethod['vnpay'], 0, ',', '.') }} đ</span>
                    </div>
                </div>

                <!-- COD -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                            💵
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-800">Thu hộ tiền mặt (COD)</p>
                            <span class="text-xs text-slate-500">Thanh toán khi nhận cá</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-slate-900 text-sm">{{ number_format($revenueByMethod['cod'], 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trạng thái tất cả đơn hàng -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-xs lg:col-span-2">
            <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span>📦</span> Trạng thái quy trình Đơn hàng & Vận chuyển GHN
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a href="{{ route('admin.orders.index') }}" class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-blue-400 transition group">
                    <span class="text-xs font-semibold text-slate-500">Tất cả đơn hàng</span>
                    <p class="text-2xl font-extrabold text-slate-800 mt-1 group-hover:text-blue-600">{{ $orderCounts['total'] }}</p>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/80 hover:border-amber-400 transition group">
                    <span class="text-xs font-semibold text-amber-700">Chờ xử lý</span>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $orderCounts['pending'] }}</p>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="p-4 rounded-xl bg-blue-50/60 border border-blue-200/80 hover:border-blue-400 transition group">
                    <span class="text-xs font-semibold text-blue-700">Đang đóng gói</span>
                    <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $orderCounts['processing'] }}</p>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-200/80 hover:border-indigo-400 transition group">
                    <span class="text-xs font-semibold text-indigo-700">Chờ GHN giao hàng</span>
                    <p class="text-2xl font-extrabold text-indigo-600 mt-1">{{ $orderCounts['shipping'] }}</p>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200/80 hover:border-emerald-400 transition group">
                    <span class="text-xs font-semibold text-emerald-700">Giao thành công</span>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $orderCounts['completed'] }}</p>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="p-4 rounded-xl bg-rose-50/60 border border-rose-200/80 hover:border-rose-400 transition group">
                    <span class="text-xs font-semibold text-rose-700">Đã hủy đơn</span>
                    <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $orderCounts['cancelled'] }}</p>
                </a>
            </div>

            <!-- Tổng quan hệ thống -->
            <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap gap-4 text-xs font-medium text-slate-500">
                <span>🐟 <strong>{{ $counts['categories'] }}</strong> loài cá trong danh mục</span>
                <span>🐠 <strong>{{ $counts['products'] }}</strong> mặt hàng cá đang bán</span>
                <span>👥 <strong>{{ $counts['users'] }}</strong> khách hàng đăng ký</span>
            </div>
        </div>
    </div>

    <!-- Biểu Đồ Doanh Thu 7 Ngày & Top Sản Phẩm Bán Chạy -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Doanh thu 7 ngày qua -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span>📈</span> Doanh thu 7 ngày gần nhất
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs font-bold text-slate-400 uppercase border-b border-slate-100">
                            <th class="py-2.5">Ngày</th>
                            <th class="py-2.5 text-center">Số đơn</th>
                            <th class="py-2.5 text-right">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($dailyRevenue as $day)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-2.5 font-medium text-slate-700">{{ $day['date'] }}</td>
                            <td class="py-2.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $day['count'] }}
                                </span>
                            </td>
                            <td class="py-2.5 text-right font-bold text-blue-600">
                                {{ number_format($day['revenue'], 0, ',', '.') }} đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top sản phẩm cá bán chạy nhất -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span>🏆</span> Top cá cảnh bán chạy nhất
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs font-bold text-slate-400 uppercase border-b border-slate-100">
                            <th class="py-2.5">Cá cảnh</th>
                            <th class="py-2.5">Loài cá</th>
                            <th class="py-2.5 text-center">Đã bán</th>
                            <th class="py-2.5 text-right">Doanh số</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topProducts as $item)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-2.5 font-semibold text-slate-800">{{ $item->product->name ?? 'Cá cảnh' }}</td>
                            <td class="py-2.5">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-200">
                                    {{ $item->product->category->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td class="py-2.5 text-center font-bold text-slate-700">{{ $item->total_quantity }}</td>
                            <td class="py-2.5 text-right font-bold text-emerald-600">{{ number_format($item->total_sales, 0, ',', '.') }} đ</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-400">Chưa có dữ liệu bán hàng.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Đơn Hàng Mới Nhất -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span>⚡</span> Đơn hàng mới nhất cần xử lý
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                Xem toàn bộ đơn hàng &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase bg-slate-50 rounded-lg">
                        <th class="p-3">Mã đơn</th>
                        <th class="p-3">Khách hàng</th>
                        <th class="p-3">Tổng thanh toán</th>
                        <th class="p-3">Thanh toán</th>
                        <th class="p-3">Mã GHN</th>
                        <th class="p-3">Trạng thái</th>
                        <th class="p-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="p-3 font-bold text-slate-900">#{{ $order->id }}</td>
                        <td class="p-3">
                            <p class="font-semibold text-slate-800">{{ $order->shipping_name ?: ($order->user->name ?? 'Khách vãng lai') }}</p>
                            <span class="text-xs text-slate-400">{{ $order->shipping_phone }}</span>
                        </td>
                        <td class="p-3 font-bold text-blue-600">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                        <td class="p-3">
                            @if($order->payment_status === 'paid')
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Đã thanh toán ({{ strtoupper($order->payment_method) }})
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Chờ thanh toán ({{ strtoupper($order->payment_method) }})
                                </span>
                            @endif
                        </td>
                        <td class="p-3">
                            @if($order->ghn_order_code)
                                <a href="https://tracking.ghn.vn/?order_code={{ $order->ghn_order_code }}" target="_blank" class="inline-flex items-center gap-1 font-mono font-bold text-xs text-indigo-700 bg-indigo-50 px-2 py-1 rounded border border-indigo-200 hover:bg-indigo-100">
                                    <span>🚚</span> {{ $order->ghn_order_code }}
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Chưa tạo vận đơn</span>
                            @endif
                        </td>
                        <td class="p-3">
                            @php
                                $statusLabels = [
                                    'pending' => ['Chờ xử lý', 'bg-amber-100 text-amber-800'],
                                    'processing' => ['Đang đóng gói', 'bg-blue-100 text-blue-800'],
                                    'shipping' => ['Chờ giao hàng (GHN)', 'bg-indigo-100 text-indigo-800'],
                                    'completed' => ['Đã giao thành công', 'bg-emerald-100 text-emerald-800'],
                                    'cancelled' => ['Đã hủy', 'bg-rose-100 text-rose-800'],
                                ];
                                $st = $statusLabels[$order->status] ?? [$order->status, 'bg-slate-100 text-slate-700'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $st[1] }}">
                                {{ $st[0] }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                Chi tiết
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400">Chưa có đơn hàng nào trong hệ thống.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
