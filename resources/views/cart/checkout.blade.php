<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán đơn hàng - Fashu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans">
    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="mb-6">
            <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-arrow-left"></i> Quay lại giỏ hàng
            </a>
            <h1 class="text-2xl font-black text-slate-900 mt-2">Thông Tin Thanh Toán & Vận Chuyển</h1>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf

            <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
                <h2 class="text-lg font-bold text-slate-800 border-b pb-2">Địa chỉ nhận hàng</h2>

                <div>
                    <label class="block text-sm font-semibold mb-1">Họ tên người nhận</label>
                    <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name ?? '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Số điện thoại</label>
                    <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500" placeholder="Nhập số điện thoại...">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tỉnh / Thành phố</label>
                        <select id="province" name="province_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500" required>
                            <option value="">Chọn Tỉnh/Thành</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Quận / Huyện</label>
                        <select id="district" name="district_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 disabled:opacity-50" required disabled>
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Phường / Xã</label>
                        <select id="ward" name="ward_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 disabled:opacity-50" required disabled>
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" id="to_district_id" name="to_district_id">
                <input type="hidden" id="to_ward_code" name="to_ward_code">

                <div>
                    <label class="block text-sm font-semibold mb-1">Địa chỉ chi tiết (Số nhà, tên đường...)</label>
                    <textarea name="shipping_address" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500" placeholder="Nhập số nhà, tên đường...">{{ old('shipping_address') }}</textarea>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit space-y-4">
                <h2 class="text-lg font-bold text-slate-800 border-b pb-2">Đơn hàng của bạn</h2>

                @php
                    $subtotal_cart = 0;
                    $total_weight = 0;
                @endphp
                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                    @foreach($cart as $id => $details)
                        @php
                            $subtotal = ($details['price'] ?? 0) * ($details['quantity'] ?? 0);
                            $subtotal_cart += $subtotal;
                            $total_weight += ($details['weight'] ?? 200) * ($details['quantity'] ?? 0);
                        @endphp
                        <div class="flex justify-between text-sm items-center">
                            <span class="text-slate-600 truncate w-36" title="{{ $details['name'] }}">
                                {{ $details['name'] }} <b class="text-slate-900">x{{ $details['quantity'] }}</b>
                            </span>
                            <span class="font-semibold text-slate-900">{{ number_format($subtotal) }} đ</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Tiền hàng:</span>
                        <span class="font-semibold">{{ number_format($subtotal_cart) }} đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Phí vận chuyển (GHN):</span>
                        <span id="shipping_fee_text" class="font-semibold text-slate-900">0 đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Khuyến mãi:</span>
                        <span id="coupon_discount_text" class="font-semibold text-emerald-600">{{ number_format(session('coupon.discount', 0), 0, ',', '.') }} đ</span>
                    </div>
                </div>

                <div class="border-t pt-3">
                    @if(session('coupon.code'))
                        <div class="mb-3 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                            Đang áp dụng: <strong>{{ session('coupon.code') }}</strong>
                        </div>
                        <button type="submit" formaction="{{ route('coupon.remove') }}" formmethod="POST" class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2 rounded-xl text-sm">
                            Xóa mã khuyến mãi
                        </button>
                    @else
                        <div class="flex gap-2">
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="Nhập mã giảm giá" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                            <button type="submit" formaction="{{ route('coupon.apply') }}" formmethod="POST" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm">Áp dụng</button>
                        </div>
                    @endif
                </div>

                <input type="hidden" name="shipping_fee" id="shipping_fee_input" value="{{ old('shipping_fee', $shippingFee) }}">
                <input type="hidden" id="total_weight" value="{{ max($total_weight, 100) }}">

                <div class="border-t pt-3 flex justify-between items-center font-bold text-base">
                    <span>Tổng thanh toán:</span>
                    <span id="grand_total_text" class="text-rose-600 text-xl" data-subtotal="{{ $subtotal_cart }}">{{ number_format(max($subtotal_cart + 0 - (session('coupon.discount', 0)), 0)) }} đ</span>
                </div>

                <div class="space-y-2 pt-2">
                    <label class="block text-sm font-semibold mb-1">Phương thức thanh toán</label>
                    <div class="space-y-2">
<<<<<<< HEAD
                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50/40 transition">
                            <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-800">Thanh toán khi nhận hàng (COD)</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 border border-blue-200 bg-blue-50/30 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50/60 transition">
                            <input type="radio" name="payment_method" value="sepay" {{ old('payment_method') === 'sepay' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-800">Chuyển khoản Ngân hàng (Quét mã VietQR / SePay)</span>
                                </div>
                                <span class="text-xs bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Tự động 24/7
                                </span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50/40 transition">
                            <input type="radio" name="payment_method" value="vnpay" {{ old('payment_method') === 'vnpay' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-800">Thanh toán qua Ví / Cổng VNPAY</span>
=======
                        <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium">Thanh toán khi nhận hàng (COD)</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="vnpay" {{ old('payment_method') === 'vnpay' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium">Thanh toán qua VNPAY</span>
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-600/20 text-center">
                    Đặt hàng ngay
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            const provinceSelect = $('#province');
            const districtSelect = $('#district');
            const wardSelect = $('#ward');
            const inputDistrictId = $('#to_district_id');
            const inputWardCode = $('#to_ward_code');
            const oldProvinceId = @json(old('province_id'));
            const oldDistrictId = @json(old('district_id', old('to_district_id')));
            const oldWardCode = @json(old('ward_id', old('to_ward_code')));

            $.ajax({
                url: '/api/ghn/provinces',
                method: 'GET',
                success: function(res) {
                    if (res.code === 200 && res.data) {
                        res.data.forEach(province => {
                            provinceSelect.append(`<option value="${province.ProvinceID}">${province.ProvinceName}</option>`);
                        });

                        if (oldProvinceId) {
                            provinceSelect.val(String(oldProvinceId)).trigger('change');
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Lỗi AJAX lấy tỉnh thành:', xhr.responseText);
                }
            });

            provinceSelect.on('change', function() {
                const provinceId = $(this).val();
                districtSelect.html('<option value="">Chọn Quận/Huyện</option>').prop('disabled', true);
                wardSelect.html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
                inputDistrictId.val('');
                inputWardCode.val('');
                resetShippingFee();

                if (!provinceId) {
                    return;
                }

                $.ajax({
                    url: '/api/ghn/districts',
                    method: 'GET',
                    data: { province_id: provinceId },
                    success: function(res) {
                        if (res.code === 200 && res.data) {
                            res.data.forEach(district => {
                                districtSelect.append(`<option value="${district.DistrictID}">${district.DistrictName}</option>`);
                            });
                            districtSelect.prop('disabled', false);

                            if (oldDistrictId) {
                                districtSelect.val(String(oldDistrictId)).trigger('change');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Lỗi AJAX lấy quận huyện:', xhr.responseText);
                    }
                });
            });

            districtSelect.on('change', function() {
                const districtId = $(this).val();
                inputDistrictId.val(districtId);
                wardSelect.html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
                inputWardCode.val('');
                resetShippingFee();

                if (!districtId) {
                    return;
                }

                $.ajax({
                    url: '/api/ghn/wards',
                    method: 'GET',
                    data: { district_id: districtId },
                    success: function(res) {
                        if (res.code === 200 && res.data) {
                            res.data.forEach(ward => {
                                wardSelect.append(`<option value="${ward.WardCode}">${ward.WardName}</option>`);
                            });
                            wardSelect.prop('disabled', false);

                            if (oldWardCode) {
                                wardSelect.val(String(oldWardCode)).trigger('change');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Lỗi AJAX lấy phường xã:', xhr.responseText);
                    }
                });

                callCalculateShipping(districtId, $('#total_weight').val());
            });

            wardSelect.on('change', function() {
                const wardCode = $(this).val();
                inputWardCode.val(wardCode);
                const districtId = inputDistrictId.val();

                if (districtId && wardCode) {
                    callCalculateShipping(districtId, $('#total_weight').val(), wardCode);
                }
            });

            function callCalculateShipping(districtId, weight, wardCode = null) {
                const data = {
                    _token: '{{ csrf_token() }}',
                    to_district_id: districtId,
                    weight: weight
                };

                if (wardCode) {
                    data.to_ward_code = wardCode;
                }

                $.ajax({
                    url: '{{ route('api.shipping.fee') }}',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            const fee = response.fee;
                            const subtotal = parseInt($('#grand_total_text').data('subtotal'));
                            const discount = parseInt('{{ session('coupon.discount', 0) }}');
                            const grandTotal = Math.max(0, subtotal + fee - discount);

                            $('#shipping_fee_text').text(fee.toLocaleString('vi-VN') + ' đ');
                            $('#shipping_fee_input').val(fee);
                            $('#coupon_discount_text').text(discount.toLocaleString('vi-VN') + ' đ');
                            $('#grand_total_text').text(grandTotal.toLocaleString('vi-VN') + ' đ');
                        }
                    },
                    error: function(xhr) {
                        console.log('Lỗi tính phí: ', xhr.responseText);
                    }
                });
            }

            function resetShippingFee() {
                $('#shipping_fee_text').text('0 đ');
                $('#shipping_fee_input').val(0);
                const subtotal = parseInt($('#grand_total_text').data('subtotal'));
                const discount = parseInt('{{ session('coupon.discount', 0) }}');
                const grandTotal = Math.max(0, subtotal - discount);
                $('#grand_total_text').text(grandTotal.toLocaleString('vi-VN') + ' đ');
            }
        });
    </script>
</body>
</html>