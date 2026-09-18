@extends('layouts.admin')

@section('title', 'Quản lý cá cảnh')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>🐠</span> Quản Lý Danh Sách Cá Cảnh
            </h1>
            <p class="text-sm text-slate-500 mt-1">Quản lý kho hàng cá cảnh, phân loại theo loài cá, giá bán và hình ảnh.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                <span>🐟</span> Quản lý loài cá
            </a>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition">
                <span>+</span> Thêm cá cảnh mới
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase border-b border-slate-200">
                        <th class="p-4">Hình ảnh</th>
                        <th class="p-4">Tên cá cảnh</th>
                        <th class="p-4">Loài cá (Danh mục)</th>
                        <th class="p-4">Giá bán</th>
                        <th class="p-4">Tồn kho</th>
                        <th class="p-4 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="p-4 w-16">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold">
                                    🐟
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-900">{{ $product->name }}</p>
                            @if($product->description)
                                <p class="text-xs text-slate-400 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</p>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($product->category)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                    <span>🐟</span> {{ $product->category->name }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">Chưa phân loại</span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-blue-600">
                            {{ number_format($product->price, 0, ',', '.') }} đ
                        </td>
                        <td class="p-4">
                            @if($product->stock > 5)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $product->stock }} con
                                </span>
                            @elseif($product->stock > 0)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    Còn {{ $product->stock }} con
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    Hết hàng
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center whitespace-nowrap space-x-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition">
                                Sửa
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
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
                        <td colspan="6" class="p-8 text-center text-slate-400">
                            Chưa có sản phẩm cá cảnh nào trong kho.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection