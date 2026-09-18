<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>@yield('title', 'Quản trị hệ thống') - Cá Cảnh Fashu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    <!-- Header Admin -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xl shadow-md shadow-blue-500/20">
                        🐠
                    </div>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg text-slate-900 tracking-tight hover:text-blue-600 transition">
                            Fashu <span class="text-blue-600">Admin</span>
                        </a>
                        <p class="text-xs text-slate-400 font-medium">Hệ thống quản lý kinh doanh cá cảnh</p>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <a href="/" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-100 transition border border-slate-200">
                        <span>🌐</span> Xem Website
                    </a>

                    <div class="flex items-center gap-2.5 pl-3 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-semibold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Quản trị viên' }}</p>
                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase">Admin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Navigation Tabs (Desktop & Mobile Friendly) -->
            <nav class="flex space-x-1 sm:space-x-2 overflow-x-auto py-2 border-t border-slate-100 text-sm no-scrollbar">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.index') ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>📊</span> Doanh thu & Tổng quan
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.categories.*') ? 'bg-cyan-600 text-white shadow-sm shadow-cyan-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>🐟</span> Danh mục loài cá
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.products.*') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>🐠</span> Quản lý cá cảnh
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>📋</span> Quản lý đơn hàng
                </a>

                <a href="{{ route('admin.coupons.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.coupons.*') ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>🎟️</span> Mã giảm giá
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-purple-600 text-white shadow-sm shadow-purple-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>👥</span> Khách hàng
                </a>
            </nav>
        </div>
    </header>

    <!-- Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs animate-fade-in">
                <span class="text-xl">✅</span>
                <div class="font-medium text-sm">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-xs animate-fade-in">
                <span class="text-xl">⚠️</span>
                <div class="font-medium text-sm">{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Cá Cảnh Fashu. Hệ thống quản trị kinh doanh cá cảnh & Tích hợp vận chuyển GHN.
        </div>
    </footer>
=======
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
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
</body>
</html>