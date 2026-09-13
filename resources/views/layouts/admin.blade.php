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
        <!-- Navbar Admin -->
        <nav class="bg-white shadow mb-6">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-6">
                    <span class="font-bold text-xl text-blue-600">⚡ Admin Fashu</span>
                    <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-blue-600 font-medium">Quản lý sản phẩm</a>
                    <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-blue-600 font-medium">Quản lý đơn hàng</a>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-blue-600 font-medium">Quản lý khách hàng</a>
                </div>
                <div>
                    <a href="/" class="text-sm text-gray-500 hover:underline">Về trang chủ website</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 pb-12">
            @yield('content')
        </main>
    </div>
</body>
</html>