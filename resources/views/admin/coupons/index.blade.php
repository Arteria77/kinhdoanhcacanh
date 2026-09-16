@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-blue-600">🎟️ Quản lý mã khuyến mãi</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.coupons.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tạo mã khuyến mãi</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-slate-50 text-left text-sm font-semibold text-slate-600">
                <tr>
                    <th class="p-3">Mã</th>
                    <th class="p-3">Loại</th>
                    <th class="p-3">Giá trị</th>
                    <th class="p-3">Đơn tối thiểu</th>
                    <th class="p-3">Lượt dùng</th>
                    <th class="p-3">Hết hạn</th>
                    <th class="p-3">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="p-3 font-bold text-slate-800">{{ $coupon->code }}</td>
                        <td class="p-3">{{ $coupon->type === 'fixed' ? 'Giảm tiền' : 'Giảm %' }}</td>
                        <td class="p-3">{{ $coupon->type === 'fixed' ? number_format($coupon->value, 0, ',', '.') . ' đ' : $coupon->value . '%' }}</td>
                        <td class="p-3">{{ number_format($coupon->min_order_value, 0, ',', '.') }} đ</td>
                        <td class="p-3">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                        <td class="p-3">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn' }}</td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="bg-yellow-500 text-white px-3 py-1.5 rounded text-sm">Sửa</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Xóa mã này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded text-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-500">Chưa có mã khuyến mãi nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
</div>
@endsection
