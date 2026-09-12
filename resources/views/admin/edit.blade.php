<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin sản phẩm</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-blue-600 mb-4">Chỉnh Sửa Sản Phẩm</h2>
        
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-600 p-3 rounded text-sm">
                <ul>@foreach ($errors->all() as $error) <li>- {{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-bold mb-1">Tên sản phẩm</label>
                <input type="text" name="name" value="{{ $product->name }}" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-1">Giá (VNĐ)</label>
                <input type="number" name="price" value="{{ $product->price }}" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-1">Số lượng tồn kho</label>
                <input type="number" name="stock" value="{{ $product->stock }}" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-1">Mô tả</label>
                <textarea name="description" rows="3" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $product->description }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-1">Hình ảnh hiện tại</label>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 object-cover rounded mb-2">
                @else
                    <p class="text-sm text-gray-500 mb-2">Chưa có ảnh</p>
                @endif
                <input type="file" name="image" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:underline">Quay lại</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật</button>
            </div>
        </form>
    </div>
</body>
</html>