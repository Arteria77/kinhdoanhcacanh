@extends('layouts.admin')

@section('title', 'Sửa loài cá: ' . $category->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                <span>✏️</span> Chỉnh Sửa Danh Mục Loài Cá
            </h1>
            <p class="text-xs text-slate-500 mt-1">Cập nhật thông tin loài cá: <strong>{{ $category->name }}</strong></p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 hover:underline">
            &larr; Quay lại danh sách
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Tên loài cá -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Tên loài cá <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            </div>

            <!-- Mô tả loài cá -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Mô tả đặc điểm loài cá
                </label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm">{{ old('description', $category->description) }}</textarea>
            </div>

            <!-- Ảnh minh họa loài cá -->
            <div>
                <label for="image" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Hình ảnh đại diện
                </label>
                @if($category->image)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500">Ảnh hiện tại</span>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                <p class="text-[11px] text-slate-400 mt-1">Để trống nếu không muốn thay đổi ảnh đại diện.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 shadow-sm shadow-cyan-600/30 transition">
                    Cập nhật thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
