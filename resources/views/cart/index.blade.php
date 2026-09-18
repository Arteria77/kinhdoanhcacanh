<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Fashu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans">

    <div class="max-w-5xl mx-auto px-4 py-10">
        <!-- Nút quay về trang chủ -->
        <div class="mb-6 flex justify-between items-center">
            <a href="/" class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-arrow-left"></i> Quay về trang chủ
            </a>
            <h1 class="text-2xl font-black text-slate-900">Giỏ Hàng Của Bạn</h1>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @if(isset($cart) && count($cart) > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="p-4">Sản phẩm</th>
                            <th class="p-4">Đơn giá</th>
                            <th class="p-4">Số lượng</th>
                            <th class="p-4">Thành tiền</th>
                            <th class="p-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $details)
                            @php 
                                $subtotal = $details['price'] * $details['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 flex items-center gap-3">
                                    @if(isset($details['image']) && $details['image'])
                                        <img src="{{ asset('storage/' . $details['image']) }}" class="w-14 h-14 object-cover rounded-xl border border-slate-200">
                                    @else
                                        <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <span class="font-bold text-slate-800">{{ $details['name'] }}</span>
                                </td>
                                <td class="p-4 font-semibold text-rose-600">
                                    {{ number_format($details['price']) }} đ
                                </td>
                                <td class="p-4">
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" max="{{ $details['stock'] ?? 99 }}" class="w-16 bg-slate-100 border border-slate-200 rounded-lg px-2 py-1.5 text-center text-sm focus:outline-none focus:border-blue-500">
                                        <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Cập nhật">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    {{ number_format($subtotal) }} đ
                                </td>
                                <td class="p-4 text-center">
                                    <!-- Form xóa sản phẩm dùng phương thức POST khớp với routes/web.php -->
                                    <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition p-2" title="Xóa sản phẩm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tổng tiền & Nút hành động -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-lg font-bold text-slate-800">
                    Tổng thanh toán: <span class="text-rose-600 text-2xl font-black ml-2">{{ number_format($total) }} đ</span>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="/" class="flex-1 sm:flex-none text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-6 py-3 rounded-xl transition text-sm">
                        Tiếp tục mua sắm
                    </a>
                    <!-- Đã trỏ đúng route checkout ở đây -->
                    <a href="{{ route('checkout') }}" class="flex-1 sm:flex-none text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-sm text-sm">
                        Tiến hành thanh toán
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center">
                <i class="fa-solid fa-cart-shopping text-slate-300 text-6xl mb-4"></i>
                <p class="text-slate-500 font-medium mb-6">Giỏ hàng của bạn đang trống.</p>
                <a href="/" class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        @endif
    </div>

</body>
</html>