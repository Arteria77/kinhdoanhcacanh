@extends('layouts.admin')

@section('title', 'Danh mục loài cá')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>🐟</span> Quản Lý Danh Mục Loài Cá
            </h1>
            <p class="text-sm text-slate-500 mt-1">Phân loại các dòng cá cảnh: Cá Koi, Cá Rồng, Cá Betta, Cá Guppy, Cá Đĩa, Cá La Hán,...</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 shadow-sm shadow-cyan-600/30 transition">
                <span>+</span> Thêm loài cá mới
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase border-b border-slate-200">
                        <th class="p-4">STT</th>
                        <th class="p-4">Hình ảnh</th>
                        <th class="p-4">Tên loài cá</th>
                        <th class="p-4">Đường dẫn (Slug)</th>
                        <th class="p-4">Mô tả đặc điểm</th>
                        <th class="p-4 text-center">Số lượng mặt hàng</th>
                        <th class="p-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($categories as $index => $category)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="p-4 font-bold text-slate-400">
                            {{ $categories->firstItem() + $index }}
                        </td>
                        <td class="p-4">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-cyan-50 border border-cyan-200 flex items-center justify-center text-xl text-cyan-600 font-bold">
                                    🐟
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-slate-900">
                            {{ $category->name }}
                        </td>
                        <td class="p-4 text-xs font-mono text-slate-500">
                            /{{ $category->slug }}
                        </td>
                        <td class="p-4 text-slate-600 max-w-xs truncate">
                            {{ $category->description ?? 'Chưa có mô tả' }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                {{ $category->products_count }} sản phẩm
                            </span>
                        </td>
                        <td class="p-4 text-center space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition">
                                Sửa
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục \'{{ $category->name }}\'? Các sản phẩm thuộc danh mục này sẽ được chuyển sang chưa phân loại.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            Chưa có danh mục loài cá nào. Hãy nhấn nút thêm mới bên trên!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
