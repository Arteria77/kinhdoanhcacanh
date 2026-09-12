<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm mới</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Thêm sản phẩm mới</h1>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block font-medium">Tên sản phẩm:</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            
            <div>
                <label class="block font-medium">Giá (VNĐ):</label>
                <input type="number" name="price" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium">Số lượng tồn kho:</label>
                <input type="number" name="stock" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium">Mô tả:</label>
                <textarea name="description" class="w-full border rounded p-2"></textarea>
            </div>

            <div>
                <label class="block font-medium">Hình ảnh:</label>
                <input type="file" name="image" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Lưu sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600">Quay lại</a>
        </form>
    </div>
</body>
</html>