<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán Chuyển khoản Ngân hàng - Đơn hàng #DH{{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="/" class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Về trang chủ
            </a>
            <div class="flex items-center gap-2 text-xs font-semibold px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                Hệ thống xác nhận tiền về tự động 24/7
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <!-- Top Banner -->
            <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900 text-white p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <span class="text-xs uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full font-bold">Thanh Toán Chuyển Khoản VietQR</span>
                    <h1 class="text-2xl md:text-3xl font-black mt-2">Đơn Hàng #DH{{ $order->id }}</h1>
                    <p class="text-blue-100 text-xs md:text-sm mt-1">Mở ứng dụng ngân hàng bất kỳ để quét mã QR hoặc chuyển khoản theo hướng dẫn</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-blue-200 block">Tổng tiền cần thanh toán:</span>
                    <span class="text-2xl md:text-3xl font-black text-amber-300">{{ number_format($amount) }} đ</span>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- QR Code Box -->
                <div class="md:col-span-5 flex flex-col items-center text-center bg-slate-50 p-6 rounded-2xl border border-slate-200/70">
                    <div class="relative bg-white p-3 rounded-2xl shadow-md border border-slate-100">
                        <img id="qr-image" src="{{ $qrUrl }}" alt="VietQR SePay" class="w-60 h-60 object-contain rounded-xl">
                        <div class="absolute -top-3 -right-3 bg-red-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow">
                            VIETQR
                        </div>
                    </div>

                    <p class="text-xs font-semibold text-slate-500 mt-4 flex items-center gap-1.5">
                        <i class="fa-solid fa-qrcode text-blue-600 text-sm"></i>
                        Quét mã bằng App Ngân hàng hoặc Ví điện tử
                    </p>
                    <span class="text-[11px] text-slate-400 mt-1">Mã QR đã chứa sẵn số tài khoản và nội dung</span>
                </div>

                <!-- Bank Info Details -->
                <div class="md:col-span-7 space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-building-columns text-blue-600"></i> Thông tin tài khoản thụ hưởng
                    </h2>

                    <div class="space-y-3 text-sm">
                        <!-- Ngân hàng -->
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-500 text-xs md:text-sm">Ngân hàng:</span>
                            <span class="font-bold text-slate-900 flex items-center gap-2">
                                <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded font-black">{{ $bankBrand }}</span>
                                (TPBank)
                            </span>
                        </div>

                        <!-- Số tài khoản -->
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-500 text-xs md:text-sm">Số tài khoản:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-base text-blue-700" id="txt-acc">{{ $accountNumber }}</span>
                                <button type="button" onclick="copyToClipboard('{{ $accountNumber }}', 'copied-acc')" class="text-xs bg-white border border-slate-200 hover:bg-blue-50 hover:text-blue-600 px-2.5 py-1 rounded-lg transition font-semibold">
                                    <span id="copied-acc">Sao chép</span>
                                </button>
                            </div>
                        </div>

                        <!-- Tên chủ tài khoản -->
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-500 text-xs md:text-sm">Chủ tài khoản:</span>
                            <span class="font-bold text-slate-900 uppercase">{{ $accountName }}</span>
                        </div>

                        <!-- Số tiền -->
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-500 text-xs md:text-sm">Số tiền:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-base text-rose-600">{{ number_format($amount) }} đ</span>
                                <button type="button" onclick="copyToClipboard('{{ $amount }}', 'copied-amount')" class="text-xs bg-white border border-slate-200 hover:bg-blue-50 hover:text-blue-600 px-2.5 py-1 rounded-lg transition font-semibold">
                                    <span id="copied-amount">Sao chép</span>
                                </button>
                            </div>
                        </div>

                        <!-- Nội dung chuyển khoản -->
                        <div class="bg-amber-50 border border-amber-200 p-3.5 rounded-xl">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-amber-900 text-xs font-bold uppercase">Nội dung chuyển khoản (Bắt buộc):</span>
                                <button type="button" onclick="copyToClipboard('{{ $transferContent }}', 'copied-content')" class="text-xs bg-white border border-amber-300 text-amber-900 hover:bg-amber-100 px-2.5 py-1 rounded-lg transition font-bold shadow-sm">
                                    <span id="copied-content">Sao chép</span>
                                </button>
                            </div>
                            <div class="font-mono font-black text-lg text-amber-700 bg-white px-3 py-1.5 rounded-lg border border-amber-200 tracking-wider">
                                {{ $transferContent }}
                            </div>
                            <p class="text-[11px] text-amber-800 mt-1.5 leading-relaxed">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                <b>Lưu ý:</b> Hãy nhập chính xác nội dung <b>{{ $transferContent }}</b> khi chuyển khoản để hệ thống tự động kích hoạt đơn hàng trong vài giây.
                            </p>
                        </div>
                    </div>

                    <!-- Status Checking Box -->
                    <div id="status-box" class="mt-4 p-4 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                            <div>
                                <p class="text-xs font-bold text-blue-900" id="status-text">Đang chờ giao dịch chuyển khoản...</p>
                                <p class="text-[11px] text-blue-700">Tự động kiểm tra mỗi 3 giây</p>
                            </div>
                        </div>
                        <button type="button" id="btn-manual-check" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-2 rounded-xl transition shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-rotate"></i> Kiểm tra ngay
                        </button>
                    </div>

                    <div id="success-box" class="hidden mt-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-center space-y-1">
                        <div class="text-xl text-emerald-600"><i class="fa-solid fa-circle-check"></i></div>
                        <p class="text-sm font-black">Thanh toán thành công!</p>
                        <p class="text-xs font-normal text-emerald-700">Hệ thống đã xác nhận tiền về, đang chuyển hướng sang trang chi tiết đơn hàng...</p>
                    </div>
                </div>
            </div>

            <!-- Footer notes -->
            <div class="bg-slate-50 border-t border-slate-100 p-4 text-center text-xs text-slate-500">
                Nếu gặp bất kỳ vấn đề nào trong quá trình thanh toán, vui lòng liên hệ Hotline/Zalo: <b>0900000000</b> để được hỗ trợ tức thì.
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text, elementId) {
            navigator.clipboard.writeText(text).then(() => {
                const el = document.getElementById(elementId);
                const originalText = el.innerText;
                el.innerText = 'Đã sao chép!';
                el.classList.add('text-emerald-600');
                setTimeout(() => {
                    el.innerText = originalText;
                    el.classList.remove('text-emerald-600');
                }, 2000);
            });
        }

        $(document).ready(function() {
            const orderId = {{ $order->id }};
            const checkUrl = "{{ route('payment.sepay.check', ['order_id' => $order->id]) }}";
            let intervalId = null;
            let isChecking = false;

            function checkPaymentStatus() {
                if (isChecking) return;
                isChecking = true;

                $.ajax({
                    url: checkUrl,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.paid) {
                            clearInterval(intervalId);
                            $('#status-box').addClass('hidden');
                            $('#success-box').removeClass('hidden');

                            setTimeout(function() {
                                window.location.href = response.redirect || "{{ route('checkout.success', ['id' => $order->id]) }}";
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        console.log('Lỗi kiểm tra SePay:', xhr.responseText);
                    },
                    complete: function() {
                        isChecking = false;
                    }
                });
            }

            // Tự động kiểm tra mỗi 3 giây
            intervalId = setInterval(checkPaymentStatus, 3000);

            // Nút bấm kiểm tra thủ công
            $('#btn-manual-check').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang check...');
                checkPaymentStatus();
                setTimeout(() => {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-rotate"></i> Kiểm tra ngay');
                }, 2000);
            });
        });
    </script>
</body>
</html>
