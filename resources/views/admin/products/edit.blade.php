<<<<<<< HEAD
@extends('layouts.admin')

@section('title', 'Sửa cá cảnh: ' . $product->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                <span>✏️</span> Chỉnh Sửa Cá Cảnh
            </h1>
            <p class="text-xs text-slate-500 mt-1">Cập nhật thông tin: <strong>{{ $product->name }}</strong></p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 hover:underline">
            &larr; Quay lại danh sách
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
            <p class="font-bold mb-1">Vui lòng kiểm tra lại:</p>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Tên cá cảnh -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Tên cá cảnh <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm">
            </div>

            <!-- Danh mục loài cá -->
            <div>
                <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Thuộc danh mục loài cá
                </label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm">
                    <option value="">-- Chọn loài cá (hoặc Chưa phân loại) --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            🐟 {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Giá & Tồn kho -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Giá bán (VNĐ) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', (int)$product->price) }}" required min="0" step="1000"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm">
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Số lượng trong bể (Tồn kho) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm">
                </div>
            </div>

            <!-- Mô tả -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Mô tả đặc điểm & cách chăm sóc
                </label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Ảnh -->
            <div>
                <label for="image" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Hình ảnh cá cảnh
                </label>
                @if($product->image)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500">Ảnh hiện tại</span>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400 mt-1">Để trống nếu không muốn đổi ảnh.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition">
                    Cập nhật cá cảnh
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
=======
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
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
