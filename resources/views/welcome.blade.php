<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashu - Thế Giới Cá Cảnh & Thủy Sinh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans">

    <!-- Top Bar -->
    <div class="bg-blue-900 text-blue-100 text-xs py-2 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <span><i class="fa-solid fa-phone mr-1"></i> Hotline: 0900000000</span>
                <span class="hidden md:inline"><i class="fa-solid fa-envelope mr-1"></i> support@fashu.aqua</span>
            </div>
            <div>Giao hàng toàn quốc - Đảm bảo cá sống khỏe mạnh 100%</div>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="text-2xl font-black text-blue-600 flex items-center gap-2">
                <i class="fa-solid fa-fish-fins text-blue-500"></i> FASHU
            </a>

            <!-- Form Tìm kiếm -->
            <form action="{{ route('shop.search') }}" method="GET" class="hidden md:flex items-center w-1/3">
                <input type="text" name="query" value="{{ request('query') }}" placeholder="Tìm kiếm cá cảnh, phụ kiện thủy sinh..." class="w-full bg-slate-100 border border-slate-200 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-r-lg hover:bg-blue-700 transition text-sm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <!-- User Auth & Actions -->
            <div class="flex items-center space-x-4">
                <!-- Nút Giỏ hàng -->
                <a href="{{ route('cart.index') }}" class="relative text-slate-700 hover:text-blue-600 font-medium text-sm flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition">
                    <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                    @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    @if(Auth::user()->role === 'admin')
<<<<<<< HEAD
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-blue-100 transition flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-gauge-high"></i> Quản trị
=======
                        <a href="{{ route('admin.products.index') }}" class="bg-rose-50 text-rose-600 border border-rose-200 px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-rose-100 transition flex items-center gap-1">
                            <i class="fa-solid fa-gear"></i> Quản lý
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
                        </a>
                    @endif
                    <a href="{{ route('profile.show') }}" class="text-slate-700 hover:text-blue-600 font-medium text-sm flex items-center gap-1">
                        <i class="fa-solid fa-user-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-600 text-sm"><i class="fa-solid fa-right-from-bracket"></i></button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 font-medium text-sm hover:underline">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm transition">Đăng ký</a>
                @endauth
            </div>
        </div>
    </header>

<<<<<<< HEAD
    @auth
        @if(!Auth::user()->hasVerifiedEmail())
            <div class="bg-amber-500 text-white px-4 py-2.5 text-sm font-medium shadow-sm">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-100"></i>
                        <span>Tài khoản của bạn (<b>{{ Auth::user()->email }}</b>) chưa được xác thực email. Hãy xác thực để có thể thêm sản phẩm vào giỏ hàng.</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('verification.send') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-white text-amber-800 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-amber-50 transition shadow-sm">
                                Gửi lại email xác thực
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <div class="max-w-7xl mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-check-circle text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('status') == 'verification-link-sent')
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-paper-plane text-emerald-500"></i> Một email xác thực mới đã được gửi tới hộp thư của bạn! Vui lòng kiểm tra email.
            </div>
        @endif
    </div>

=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    <!-- Hero Banner -->
    <section class="max-w-7xl mx-auto px-4 my-6">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-800 rounded-2xl p-8 md:p-12 text-white flex flex-col md:flex-row items-center justify-between shadow-xl">
            <div class="max-w-xl mb-6 md:mb-0">
                <span class="bg-blue-500/40 border border-blue-300/30 text-blue-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Thế giới thủy sinh cao cấp</span>
                <h1 class="text-3xl md:text-5xl font-extrabold mt-3 mb-4 leading-tight">Chuyên Cung Cấp Các Dòng Cá Cảnh Đẹp & Độc Lạ</h1>
                <p class="text-blue-100 mb-6 text-sm md:text-base leading-relaxed">Mang thiên nhiên đại dương vào không gian sống của bạn với các giống cá khỏe mạnh, màu sắc rực rỡ và dịch vụ chuyên nghiệp tại Hà Nội.</p>
                <a href="#products-section" class="bg-white text-blue-700 font-bold px-6 py-3 rounded-xl shadow-lg hover:bg-blue-50 transition inline-block text-sm">Khám phá ngay &rarr;</a>
            </div>
            <div class="text-blue-300/20 text-8xl md:text-9xl">
                <i class="fa-solid fa-fish"></i>
            </div>
        </div>
    </section>

    <!-- Main Content: Danh sách sản phẩm -->
    <main id="products-section" class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                    @if(isset($keyword))
                        Kết quả tìm kiếm cho: "{{ $keyword }}"
                    @else
                        🐠 Danh Sách Cá Cảnh Nổi Bật
                    @endif
                </h2>
                <p class="text-slate-500 text-sm mt-1">Cập nhật các mẫu cá mới nhất, khỏe mạnh và thuần dưỡng tại cửa hàng.</p>
            </div>
            @if(isset($keyword))
                <a href="/" class="text-sm bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-1.5 rounded-lg transition font-medium">Quay lại tất cả</a>
            @else
                <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-3 py-1 rounded-full">Sản phẩm chính hãng</span>
            @endif
        </div>
        
<<<<<<< HEAD
        <!-- Danh mục các loài cá cảnh -->
        @if(isset($categories) && $categories->count() > 0)
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Khám phá theo danh mục loài cá</p>
            <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
                <a href="{{ route('home') }}#products-section" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('category_id') ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Tất cả các loài
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('home', ['category_id' => $cat->id]) }}#products-section" 
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ request('category_id') == $cat->id ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    <span>🐟</span>
                    <span>{{ $cat->name }}</span>
                    @if($cat->products_count > 0)
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ request('category_id') == $cat->id ? 'bg-blue-700 text-blue-100' : 'bg-slate-100 text-slate-500' }}">
                        {{ $cat->products_count }}
                    </span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden flex flex-col justify-between border border-slate-100 group">
                    <div>
                        <!-- Khung ảnh sản phẩm -->
                        <div class="relative overflow-hidden bg-slate-100 h-52">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                    <i class="fa-solid fa-image text-3xl"></i>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                                Kho: {{ $product->stock }}
                            </span>
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="p-5">
<<<<<<< HEAD
                            @if($product->category)
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-cyan-50 text-cyan-700 border border-cyan-200 mb-1.5">
                                    {{ $product->category->name }}
                                </span>
                            @endif
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
                            <h3 class="font-bold text-slate-800 text-base mb-2 line-clamp-1 group-hover:text-blue-600 transition">{{ $product->name }}</h3>
                            <div class="text-rose-600 font-black text-lg mb-4">
                                {{ number_format($product->price) }} <span class="text-xs font-semibold">đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Nút bấm chi tiết -->
                    <div class="p-5 pt-0">
                        <a href="{{ route('products.show', $product->id) }}" class="w-full block text-center bg-slate-900 text-white py-2.5 rounded-xl hover:bg-blue-600 transition font-semibold text-sm shadow-sm">
                            Xem thông tin sản phẩm
                      </a>
                  </div>
            </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <i class="fa-solid fa-fish text-slate-300 text-5xl mb-3"></i>
                    <p class="text-slate-500 font-medium">Không tìm thấy sản phẩm cá cảnh nào phù hợp.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 mt-16 text-sm">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <h3 class="text-white font-bold text-base mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-fish-fins text-blue-500"></i> Fashu Aqua
                </h3>
                <p class="text-slate-400 text-xs leading-relaxed">Chuyên cung cấp các dòng cá cảnh thủy sinh, tép cảnh và thiết bị hồ cá uy tín hàng đầu. Cam kết chất lượng và sức khỏe cá khi đến tay khách hàng.</p>
            </div>
            <div>
                <h3 class="text-white font-bold text-base mb-3">Thông Tin Liên Hệ</h3>
                <p class="text-xs mb-2"><i class="fa-solid fa-location-dot mr-2 text-blue-500"></i> Địa chỉ: Hà Nội, Việt Nam</p>
                <p class="text-xs mb-2"><i class="fa-solid fa-phone mr-2 text-blue-500"></i> Hotline/Zalo: 0900000000</p>
            </div>
            <div>
                <h3 class="text-white font-bold text-base mb-3">Hỗ Trợ Khách Hàng</h3>
                <p class="text-xs mb-1 hover:text-white cursor-pointer transition">Chính sách bảo hành cá sống</p>
                <p class="text-xs mb-1 hover:text-white cursor-pointer transition">Hướng dẫn mua hàng & thanh toán</p>
                <p class="text-xs mb-1 hover:text-white cursor-pointer transition">Chính sách vận chuyển toàn quốc</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 border-t border-slate-800 pt-6 text-center text-slate-500 text-xs">
            &copy; 2026 Fashu. All rights reserved. Designed with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>
</html>