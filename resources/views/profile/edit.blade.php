<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân - Shop Cá Cảnh</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">🐠 Shop Cá Cảnh</a>
            <a href="/" class="text-gray-600 hover:text-blue-600">Trang chủ</a>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Cập nhật thông tin cá nhân</h2>

            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-600 p-3 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Họ và tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email (Không thể đổi)</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-500">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700">Lưu thay đổi</button>
            </form>
        </div>
    </main>
</body>
</html>