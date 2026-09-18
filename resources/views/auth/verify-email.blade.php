<<<<<<< HEAD
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Email - Fashu Shop Cá Cảnh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 text-slate-800">
    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-xl w-full max-w-lg border border-slate-100 text-center">
        <!-- Icon & Header -->
        <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-inner">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>

        <h1 class="text-2xl font-black text-slate-900 mb-2">Xác Thực Địa Chỉ Email</h1>
        <p class="text-sm text-slate-500 mb-6 leading-relaxed">
            Cảm ơn bạn đã đăng ký tài khoản tại <b>Fashu Aqua</b>. Để bảo vệ tài khoản và kích hoạt quyền thêm giỏ hàng, bạn vui lòng xác nhận email.
        </p>

        <!-- Hộp thông tin Email người nhận -->
        <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 mb-6 text-sm">
            <span class="text-slate-500 block text-xs mb-1">Email cần xác nhận:</span>
            <span class="font-bold text-blue-700 text-base break-all">
                {{ auth()->user()->email ?? 'Email của bạn' }}
            </span>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl text-sm font-medium flex items-center gap-3 text-left">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg flex-shrink-0"></i>
                <span>Một email xác thực mới đã được gửi thành công! Vui lòng kiểm tra hộp thư (và cả mục <b>Spam / Thư rác</b>).</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-sm font-medium flex items-center gap-3 text-left">
                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl text-sm font-medium flex items-center gap-3 text-left">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="text-left bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-200/60 text-xs text-slate-600 space-y-2">
            <div class="font-semibold text-slate-700 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-info text-blue-500"></i> Hướng dẫn các bước xác thực:
            </div>
            <ol class="list-decimal list-inside space-y-1 pl-1">
                <li>Mở ứng dụng hoặc trang web Gmail của bạn.</li>
                <li>Tìm thư từ <b>Fashu - Shop Cá Cảnh</b>.</li>
                <li>Nhấn vào nút <b>"Xác Thực Email Ngay"</b> trong thư.</li>
                <li>Hệ thống sẽ tự động kích hoạt và bạn có thể thêm sản phẩm vào giỏ hàng ngay lập tức!</li>
            </ol>
        </div>

        <!-- Các nút hành động -->
        <div class="space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Gửi lại email xác thực
                </button>
            </form>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-sm">
                <a href="/" class="text-slate-600 hover:text-blue-600 font-medium transition flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Về trang chủ
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-rose-600 hover:text-rose-700 font-semibold transition">
                        Đăng xuất <i class="fa-solid fa-right-from-bracket ml-1 text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
=======
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
