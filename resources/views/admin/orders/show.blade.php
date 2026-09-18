@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Đơn Hàng #{{ $order->id }}
                </h1>
                @php
                    $statusStyles = [
                        'pending' => ['Chờ xử lý', 'bg-amber-100 text-amber-800 border-amber-200'],
                        'processing' => ['Đang xử lý', 'bg-blue-100 text-blue-800 border-blue-200'],
                        'shipping' => ['Chờ giao hàng (GHN)', 'bg-indigo-100 text-indigo-800 border-indigo-200 font-bold'],
                        'completed' => ['Đã giao thành công', 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                        'cancelled' => ['Đã hủy', 'bg-rose-100 text-rose-800 border-rose-200'],
                    ];
                    $st = $statusStyles[$order->status] ?? [$order->status, 'bg-slate-100 text-slate-700'];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $st[1] }}">
                    {{ $st[0] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Đặt ngày {{ $order->created_at->format('d/m/Y H:i:s') }} &bull; Cổng thanh toán: <strong class="uppercase text-slate-700">{{ $order->payment_method }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition">
                &larr; Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cột Trái: Vận Đơn GHN & Sản Phẩm (2 phần 3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Vận Chuyển Giao Hàng Nhanh (GHN) -->
            <div class="bg-white rounded-2xl border border-indigo-200 shadow-xs p-5 sm:p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-md shadow-indigo-600/20">
                            🚚
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Vận Chuyển Giao Hàng Nhanh (GHN)</h2>
                            <p class="text-xs text-slate-500">Đối tác vận chuyển cá cảnh toàn quốc</p>
                        </div>
                    </div>

                    @if($order->ghn_order_code)
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-mono font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                Mã: {{ $order->ghn_order_code }}
                            </span>
                            <a href="{{ $trackingUrl }}" target="_blank" class="px-3 py-1 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition">
                                Tra cứu hành trình &nearr;
                            </a>
                        </div>
                    @else
                        <form action="{{ route('admin.orders.createGhn', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn đẩy đơn hàng này sang GHN để lấy mã vận đơn ngay?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/30 transition">
                                <span>🚀</span> Tạo Mã Vận Đơn GHN Ngay
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Chi tiết bưu gửi GHN -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Mã vận đơn GHN</span>
                        <span class="font-bold text-slate-800 font-mono text-sm mt-0.5 block">
                            {{ $order->ghn_order_code ?: 'Chưa tạo vận đơn' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Cước vận chuyển</span>
                        <span class="font-bold text-blue-600 text-sm mt-0.5 block">
                            {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Tiền thu hộ COD</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                            {{ $order->payment_status === 'paid' ? '0 đ (Đã thanh toán)' : number_format($order->total_price, 0, ',', '.') . ' đ' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Gói cước GHN</span>
                        <span class="font-semibold text-slate-700 mt-0.5 block">
                            Chuẩn E-Commerce (Đi bộ/Xe tải)
                        </span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-slate-50 rounded-xl text-xs text-slate-500 flex items-center gap-2">
                    <span>ℹ️</span>
                    <span>Quy định đồng kiểm: <strong>CHO XEM HÀNG KHÔNG CHO THỬ</strong>. Hàng hóa được đóng thùng xốp chuyên dụng đảm bảo oxy cho cá.</span>
                </div>
            </div>

            <!-- Danh sách sản phẩm cá cảnh -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
                <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span>🐠</span> Sản Phẩm Cá Cảnh Đặt Mua ({{ $order->orderItems->count() }} mặt hàng)
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-slate-400 uppercase bg-slate-50 rounded-lg">
                                <th class="p-3">Hình ảnh</th>
                                <th class="p-3">Tên sản phẩm</th>
                                <th class="p-3">Đơn giá</th>
                                <th class="p-3 text-center">Số lượng</th>
                                <th class="p-3 text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($order->orderItems as $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="p-3 w-14">
                                    @if(!empty($item->product->image))
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 text-lg font-bold">
                                            🐟
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <p class="font-bold text-slate-900">{{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}</p>
                                    @if(isset($item->product->category))
                                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                            {{ $item->product->category->name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-slate-600 font-medium">
                                    {{ number_format($item->price, 0, ',', '.') }} đ
                                </td>
                                <td class="p-3 text-center font-bold text-slate-800">
                                    {{ $item->quantity }}
                                </td>
                                <td class="p-3 text-right font-bold text-blue-600">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tổng kết tài chính đơn -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col items-end space-y-1.5 text-sm">
                    @php
                        $subtotal = 0;
                        foreach($order->orderItems as $it) {
                            $subtotal += $it->price * $it->quantity;
                        }
                    @endphp
                    <div class="flex justify-between w-64 text-slate-500 text-xs">
                        <span>Tiền hàng (Tạm tính):</span>
                        <span class="font-semibold text-slate-800">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                    </div>

                    @if($order->discount_amount > 0)
                    <div class="flex justify-between w-64 text-emerald-600 text-xs">
                        <span>Mã giảm giá ({{ $order->coupon_code }}):</span>
                        <span class="font-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }} đ</span>
                    </div>
                    @endif

                    <div class="flex justify-between w-64 text-slate-500 text-xs">
                        <span>Cước vận chuyển GHN:</span>
                        <span class="font-semibold text-slate-800">+{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ</span>
                    </div>

                    <div class="flex justify-between w-64 pt-2 border-t border-slate-200 text-slate-900 font-extrabold text-base">
                        <span>Tổng thanh toán:</span>
                        <span class="text-blue-600">{{ number_format($order->total_price, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột Phải: Thông tin Người nhận & Cập nhật trạng thái (1 phần 3) -->
        <div class="space-y-6">
            <!-- Card Người Nhận & Địa chỉ giao cá -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span>📍</span> Thông Tin Giao Hàng
                </h2>
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Họ và tên người nhận</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                            {{ $order->shipping_name ?: ($order->user->name ?? 'N/A') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">Số điện thoại liên lạc</span>
                        <span class="font-bold text-slate-800 text-sm font-mono mt-0.5 block">
                            {{ $order->shipping_phone ?: 'N/A' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">Địa chỉ giao hàng</span>
                        <span class="font-medium text-slate-700 mt-0.5 block">
                            {{ $order->shipping_address ?: 'N/A' }}
                        </span>
                    </div>

                    @if($order->to_district_id || $order->to_ward_code)
                    <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 space-y-0.5">
                        <p>Mã Quận/Huyện GHN: <strong class="text-slate-600 font-mono">{{ $order->to_district_id ?? 'N/A' }}</strong></p>
                        <p>Mã Phường/Xã GHN: <strong class="text-slate-600 font-mono">{{ $order->to_ward_code ?? 'N/A' }}</strong></p>
                    </div>
                    @endif

                    @if($order->user)
                    <div class="pt-2 border-t border-slate-100 text-slate-500">
                        <span>Tài khoản đặt hàng: <strong>{{ $order->user->name }}</strong> ({{ $order->user->email }})</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card Cập Nhật Trạng Thái Đơn Hàng -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span>⚙️</span> Cập Nhật Tiến Độ Đơn Hàng
                </h2>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- Trạng thái đơn -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Trạng thái đơn hàng</label>
                        <select name="status" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý (Khách vừa đặt)</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý / Đóng gói cá cảnh</option>
                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Chờ giao hàng (Đã có mã GHN)</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã hoàn thành / Khách đã nhận cá</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy đơn hàng</option>
                        </select>
                    </div>

                    <!-- Trạng thái thanh toán -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Trạng thái thanh toán</label>
                        <select name="payment_status" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500">
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán (Paid)</option>
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán (Pending)</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thất bại / Hủy (Failed)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/30 transition">
                        Lưu thay đổi trạng thái
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection