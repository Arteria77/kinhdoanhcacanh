<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm vào giỏ hàng thành công - Fashu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full mx-4 bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center space-y-4">
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl">
            <i class="fa-solid fa-check"></i>
        </div>
        
        <h1 class="text-xl font-black text-slate-900">Thêm vào giỏ hàng thành công!</h1>
        <p class="text-sm text-slate-600">Sản phẩm của bạn đã được cập nhật vào giỏ hàng.</p>

        <div class="pt-4 flex flex-col gap-3">
            <a href="{{ route('cart.index') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-sm text-center text-sm">
                Xem giỏ hàng & Thanh toán
            </a>
            <a href="{{ url('/') }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 rounded-xl transition text-center text-sm">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>
</body>
</html>