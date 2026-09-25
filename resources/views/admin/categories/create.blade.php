@extends('layouts.admin')

@section('title', 'Thêm loài cá mới')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                <span>➕</span> Thêm Danh Mục Loài Cá Mới
            </h1>
            <p class="text-xs text-slate-500 mt-1">Tạo nhóm phân loại mới cho các dòng cá cảnh bán trên shop.</p>
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
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Tên loài cá -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Tên loài cá <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       placeholder="Ví dụ: Cá Koi Nhật, Cá Rồng Huyết Long, Cá Betta..."
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 shadow-sm shadow-cyan-600/30 transition">
                    Lưu danh mục loài cá
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
