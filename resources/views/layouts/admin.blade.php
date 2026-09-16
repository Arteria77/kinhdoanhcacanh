<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị Fashu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-white shadow mb-6">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-xl text-blue-600">⚡ Admin Fashu</span>
                </div>

                <div>
                    <a href="/" class="text-sm text-gray-500 hover:underline">Về trang chủ website</a>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 pb-12">
            <div class="{{ request()->routeIs('admin.orders.*') || request()->routeIs('admin.coupons.*') ? '' : 'flex gap-6 items-start' }}">
                @unless(request()->routeIs('admin.orders.*') || request()->routeIs('admin.coupons.*'))
                <aside class="w-72 bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-6">
                    <div class="mb-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Menu quản trị</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('admin.products.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            Quản lý sản phẩm
                        </a>

                        <a href="{{ route('admin.orders.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            Quản lý đơn hàng
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            Quản lý khách hàng
                        </a>

                        <a href="{{ route('admin.coupons.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('admin.coupons.*') ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-gray-700 hover:bg-gray-50 border border-transparent' }}">
                            Mã khuyến mãi
                        </a>
                    </nav>
                </aside>
                @endunless

                <div class="{{ request()->routeIs('admin.orders.*') || request()->routeIs('admin.coupons.*') ? '' : 'flex-1 min-w-0' }}">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>