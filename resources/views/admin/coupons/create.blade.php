@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-bold text-blue-600 mb-6">Thêm mã khuyến mãi</h1>

    @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">Mã khuyến mãi</label>
            <input type="text" name="code" value="{{ old('code') }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Loại giảm giá</label>
            <select name="type" class="w-full border border-slate-200 rounded-lg px-3 py-2">
                <option value="fixed">Giảm tiền cố định</option>
                <option value="percent">Giảm theo phần trăm</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Giá trị</label>
            <input type="number" name="value" value="{{ old('value') }}" min="0" required class="w-full border border-slate-200 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Đơn tối thiểu</label>
            <input type="number" name="min_order_value" value="{{ old('min_order_value', 0) }}" min="0" required class="w-full border border-slate-200 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Giới hạn lượt dùng</label>
            <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1" class="w-full border border-slate-200 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Ngày hết hạn</label>
            <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Lưu mã</button>
            <a href="{{ route('admin.coupons.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-300">Quay lại</a>
        </div>
    </form>
</div>
@endsection
