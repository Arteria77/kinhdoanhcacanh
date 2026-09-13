<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - Shop Cá Cảnh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto px-4 py-10">
        <a href="/" class="text-blue-600 hover:underline mb-6 inline-block">&larr; Quay lại trang chủ</a>
        
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-80 object-cover rounded-lg">
                @else
                    <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-400 rounded-lg">Không có ảnh</div>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-3">{{ $product->name }}</h1>
                <p class="text-2xl font-bold text-blue-600 mb-4">{{ number_format($product->price) }} đ</p>
                <p class="text-gray-600 mb-4"><b>Tồn kho:</b> {{ $product->stock }} con</p>
                <div class="mb-6">
                    <h3 class="font-bold text-gray-700 mb-1">Mô tả chi tiết:</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $product->description ?? 'Chưa có mô tả cho sản phẩm này.' }}</p>
                </div>

                <!-- Thêm form nút Thêm vào giỏ hàng -->
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="flex items-center space-x-4 mb-4">
                        <label for="quantity" class="font-medium text-gray-700">Số lượng:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                        🛒 Thêm vào giỏ hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>