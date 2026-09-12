<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Chỉnh sửa sản phẩm: {{ $product->name }}</h1>
        
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium">Tên sản phẩm:</label>
                <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded p-2" required>
            </div>
            
            <div>
                <label class="block font-medium">Giá (VNĐ):</label>
                <input type="number" name="price" value="{{ $product->price }}" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium">Số lượng tồn kho:</label>
                <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium">Mô tả:</label>
                <textarea name="description" class="w-full border rounded p-2">{{ $product->description }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Hình ảnh hiện tại:</label>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-20 h-20 object-cover mb-2 rounded">
                @endif
                <input type="file" name="image" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600">Quay lại</a>
        </form>
    </div>
</body>
</html>