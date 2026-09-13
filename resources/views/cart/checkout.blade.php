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

        <form action="{{ route('orders.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            
            <!-- Cột thông tin giao hàng -->
            <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
                <h2 class="text-lg font-bold text-slate-800 border-b pb-2">Địa chỉ nhận hàng</h2>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Họ tên người nhận</label>
                    <input type="text" name="shipping_name" value="{{ auth()->user()->name ?? '' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Số điện thoại</label>
                    <input type="text" name="shipping_phone" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500" placeholder="Nhập số điện thoại...">
                </div>

                <!-- CHỌN TỈNH / HUYỆN / XÃ -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tỉnh / Thành phố</label>
                        <select id="province" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500" required>
                            <option value="">Chọn Tỉnh/Thành</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Quận / Huyện</label>
                        <select id="district" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 disabled:opacity-50" required disabled>
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Phường / Xã</label>
                        <select id="ward" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 disabled:opacity-50" required disabled>
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" id="to_district_id" name="to_district_id">
                <input type="hidden" id="to_ward_code" name="to_ward_code">

                <div>
                    <label class="block text-sm font-semibold mb-1">Địa chỉ chi tiết (Số nhà, tên đường...)</label>
                    <textarea name="shipping_address" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500" placeholder="Nhập số nhà, tên đường..."></textarea>
                </div>
            </div>

            <!-- Cột tóm tắt đơn hàng -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit space-y-4">
                <h2 class="text-lg font-bold text-slate-800 border-b pb-2">Đơn hàng của bạn</h2>
                
                @php 
                    $subtotal_cart = 0; 
                    $total_weight = 0;
                @endphp
                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                    @foreach($cart as $id => $details)
                        @php 
                            $subtotal = $details['price'] * $details['quantity']; 
                            $subtotal_cart += $subtotal;
                            $total_weight += ($details['weight'] ?? 200) * $details['quantity'];
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
                </div>

                <input type="hidden" name="shipping_fee" id="shipping_fee_input" value="0">
                <input type="hidden" id="total_weight" value="{{ max($total_weight, 100) }}">

                <div class="border-t pt-3 flex justify-between items-center font-bold text-base">
                    <span>Tổng thanh toán:</span>
                    <span id="grand_total_text" class="text-rose-600 text-xl" data-subtotal="{{ $subtotal_cart }}">{{ number_format($subtotal_cart) }} đ</span>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-sm text-center">
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

            // 1. Load Tỉnh/Thành
            $.ajax({
                url: '/api/ghn/provinces',
                method: 'GET',
                success: function(res) {
                    console.log("Provinces response:", res);
                    if(res.code === 200 && res.data) {
                        res.data.forEach(province => {
                            provinceSelect.append(`<option value="${province.ProvinceID}">${province.ProvinceName}</option>`);
                        });
                    } else {
                        console.error("GHN trả về lỗi lấy tỉnh thành:", res);
                    }
                },
                error: function(xhr) {
                    console.error("Lỗi AJAX lấy tỉnh thành:", xhr.responseText);
                }
            });

            // 2. Chọn Tỉnh -> Load Quận/Huyện
            provinceSelect.on('change', function() {
                let provinceId = $(this).val();
                districtSelect.html('<option value="">Chọn Quận/Huyện</option>').prop('disabled', true);
                wardSelect.html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
                inputDistrictId.val('');
                inputWardCode.val('');
                resetShippingFee();

                if (provinceId) {
                    $.ajax({
                        url: '/api/ghn/districts',
                        method: 'GET',
                        data: { province_id: provinceId },
                        success: function(res) {
                            if(res.code === 200 && res.data) {
                                res.data.forEach(district => {
                                    districtSelect.append(`<option value="${district.DistrictID}">${district.DistrictName}</option>`);
                                });
                                districtSelect.prop('disabled', false);
                            }
                        },
                        error: function(xhr) {
                            console.error("Lỗi AJAX lấy quận huyện:", xhr.responseText);
                        }
                    });
                }
            });

            // 3. Chọn Quận/Huyện -> Load Phường/Xã & Tính phí ship
            districtSelect.on('change', function() {
                let districtId = $(this).val();
                inputDistrictId.val(districtId);
                wardSelect.html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
                inputWardCode.val('');
                resetShippingFee();

                if (districtId) {
                    $.ajax({
                        url: '/api/ghn/wards',
                        method: 'GET',
                        data: { district_id: districtId },
                        success: function(res) {
                            if(res.code === 200 && res.data) {
                                res.data.forEach(ward => {
                                    wardSelect.append(`<option value="${ward.WardCode}">${ward.WardName}</option>`);
                                });
                                wardSelect.prop('disabled', false);
                            }
                        },
                        error: function(xhr) {
                            console.error("Lỗi AJAX lấy phường xã:", xhr.responseText);
                        }
                    });

                    callCalculateShipping(districtId, $('#total_weight').val());
                }
            });

            // 4. Chọn Phường/Xã
            wardSelect.on('change', function() {
                let wardCode = $(this).val();
                inputWardCode.val(wardCode);
                let districtId = inputDistrictId.val();
                if (districtId) {
                    callCalculateShipping(districtId, $('#total_weight').val(), wardCode);
                }
            });

            function callCalculateShipping(districtId, weight, wardCode = null) {
                let data = {
                    _token: "{{ csrf_token() }}",
                    to_district_id: districtId,
                    weight: weight
                };
                if (wardCode) data.to_ward_code = wardCode;

                $.ajax({
                    url: "{{ route('api.shipping.fee') }}",
                    method: "POST",
                    data: data,
                    success: function(response) {
                        if(response.success) {
                            let fee = response.fee;
                            let subtotal = parseInt($('#grand_total_text').data('subtotal'));
                            let grandTotal = subtotal + fee;

                            $('#shipping_fee_text').text(fee.toLocaleString('vi-VN') + ' đ');
                            $('#shipping_fee_input').val(fee);
                            $('#grand_total_text').text(grandTotal.toLocaleString('vi-VN') + ' đ');
                        }
                    },
                    error: function(xhr) {
                        console.log("Lỗi tính phí: ", xhr.responseText);
                    }
                });
            }

            function resetShippingFee() {
                $('#shipping_fee_text').text('0 đ');
                $('#shipping_fee_input').val(0);
                let subtotal = parseInt($('#grand_total_text').data('subtotal'));
                $('#grand_total_text').text(subtotal.toLocaleString('vi-VN') + ' đ');
            }
        });
    </script>
</body>
</html>