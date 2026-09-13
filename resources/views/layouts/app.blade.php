<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Nút nổi cố định góc dưới bên phải (Tự đổi chức năng nếu là Admin) -->
            @php
                $isAdmin = auth()->check() && (optional(auth()->user())->role === 'admin' || optional(auth()->user())->is_admin == 1);
            @endphp

            @if($isAdmin)
                <!-- Nút dành riêng cho Admin: Chuyển đến trang Quản lý đơn hàng -->
                <a href="{{ route('admin.orders.index') }}" class="fixed bottom-6 right-6 bg-purple-600 hover:bg-purple-700 text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-2xl z-50 transition duration-200 group" title="Quản lý đơn hàng">
                    📋
                    <span class="absolute -top-2 -right-2 bg-indigo-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md whitespace-nowrap">
                        Admin
                    </span>
                </a>
            @else
                <!-- Nút dành cho Khách hàng: Xem giỏ hàng -->
                <a href="{{ route('cart.index') }}" class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-2xl z-50 transition duration-200 group" title="Xem giỏ hàng">
                    🛒
                    @php
                        $cartCount = session('cart') ? count(session('cart')) : 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-md">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            @endif
        </div>
    </body>
</html>