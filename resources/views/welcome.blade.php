<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            <!-- Form Tìm kiếm & Nút AI -->
            <div class="hidden md:flex items-center gap-3 w-1/2 justify-center">
                <form action="{{ route('shop.search') }}" method="GET" class="flex items-center w-2/3">
                    <input type="text" name="query" value="{{ request('query') }}" placeholder="Tìm kiếm cá cảnh, phụ kiện thủy sinh..." class="w-full bg-slate-100 border border-slate-200 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:bg-white focus:border-blue-500 transition">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-r-lg hover:bg-blue-700 transition text-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <!-- Nút Lựa chọn AI trên Header -->
                <button type="button" onclick="openAiModal()" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-3.5 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 shadow-md shadow-purple-200 animate-pulse">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Tư vấn AI</span>
                </button>
            </div>

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
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-blue-100 transition flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-gauge-high"></i> Quản trị
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

    <!-- Hero Banner -->
    <section class="max-w-7xl mx-auto px-4 my-6">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-800 rounded-2xl p-8 md:p-12 text-white flex flex-col md:flex-row items-center justify-between shadow-xl">
            <div class="max-w-xl mb-6 md:mb-0">
                <div class="flex items-center gap-2 mb-3">
                    <span class="bg-blue-500/40 border border-blue-300/30 text-blue-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Thế giới thủy sinh cao cấp</span>
                    <span class="bg-purple-500/50 border border-purple-300/30 text-purple-100 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-robot"></i> Trợ lý AI 24/7
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold mt-1 mb-4 leading-tight">Chuyên Cung Cấp Các Dòng Cá Cảnh Đẹp & Độc Lạ</h1>
                <p class="text-blue-100 mb-6 text-sm md:text-base leading-relaxed">Mang thiên nhiên đại dương vào không gian sống của bạn với các giống cá khỏe mạnh, màu sắc rực rỡ và dịch vụ tư vấn AI tự động chuyên nghiệp.</p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="#products-section" class="bg-white text-blue-700 font-bold px-6 py-3 rounded-xl shadow-lg hover:bg-blue-50 transition inline-block text-sm">Khám phá ngay &rarr;</a>
                    <!-- Nút trải nghiệm AI -->
                    <button type="button" onclick="openAiModal()" class="bg-indigo-900/80 hover:bg-indigo-900 text-white border border-indigo-400/40 font-bold px-5 py-3 rounded-xl shadow-lg transition text-sm flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-amber-300"></i>
                        <span>Tạo gợi ý cá bằng AI</span>
                    </button>
                </div>
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
                            @if($product->category)
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-cyan-50 text-cyan-700 border border-cyan-200 mb-1.5">
                                    {{ $product->category->name }}
                                </span>
                            @endif
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

    <!-- ==================== WIDGET AI TƯ VẤN THỦY SINH (GÓC PHẢI MÀN HÌNH) ==================== -->
    <div id="fashu-ai-widget-box" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <!-- Nút Bật AI Float Button -->
        
        <!-- Khung Cửa Sổ AI Popup -->
        <div id="fashu-ai-popup" style="display: none; width: 380px; max-width: 90vw; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; position: absolute; bottom: 65px; right: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; flex-direction: column;">
            <!-- Header AI Popup -->
            <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #fff; padding: 14px 16px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="background: rgba(255,255,255,0.2); padding: 6px; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: justify-center; justify-content: center; font-size: 12px;">✨</span>
                    <div>
                        <strong style="font-size: 14px; display: block; line-height: 1.2;">Fashu AI Smart Advisor</strong>
                        <small style="font-size: 10px; opacity: 0.8; display: block;">Tư vấn phối cảnh & chọn cá tự động</small>
                    </div>
                </div>
                <button type="button" onclick="toggleAiWidget()" style="background: rgba(255,255,255,0.2); color: #fff; border: none; width: 26px; height: 26px; cursor: pointer; font-weight: bold; border-radius: 50%; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <!-- Gợi ý mẫu câu hỏi (Prompts) -->
            <div style="background: #f8fafc; padding: 8px 12px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 6px; overflow-x: auto; white-space: nowrap; font-size: 11px;">
                <button type="button" onclick="sendQuickAiPrompt('Gợi ý cá cho bể 60cm')" style="background: #e0e7ff; color: #3730a3; border: none; padding: 4px 8px; border-radius: 12px; cursor: pointer; font-weight: 600;">🐠 Bể 60cm nuôi cá gì?</button>
                <button type="button" onclick="sendQuickAiPrompt('Cá bảy màu nuôi chung với cá nào?')" style="background: #f3e8ff; color: #6b21a8; border: none; padding: 4px 8px; border-radius: 12px; cursor: pointer; font-weight: 600;">🌿 Cá chung đàn</button>
                <button type="button" onclick="sendQuickAiPrompt('Làm sao xử lý nước hồ bị đục?')" style="background: #fce7f3; color: #9d174d; border: none; padding: 4px 8px; border-radius: 12px; cursor: pointer; font-weight: 600;">💧 Xử lý nước đục</button>
            </div>

            <!-- Khung chứa tin nhắn AI -->
            <div id="fashu-ai-messages" style="height: 300px; overflow-y: auto; padding: 12px; background: #fafafa; font-size: 13px; display: flex; flex-direction: column; gap: 10px;">
                <div style="text-align: left;">
                    <div style="display: inline-block; padding: 8px 12px; border-radius: 12px 12px 12px 2px; background: #e0e7ff; color: #1e1b4b; max-width: 90%; word-break: break-word; line-height: 1.4;">
                        👋 Xin chào! Tôi là **AI Fashu Aqua**. Bạn cần tư vấn chọn loại cá, phối cảnh bể thủy sinh hay cách chăm sóc cá cảnh hôm nay?
                    </div>
                </div>
            </div>

            <!-- Khung nhập tin nhắn AI -->
            <div style="padding: 10px; border-top: 1px solid #e2e8f0; display: flex; background: #fff; gap: 6px;">
                <input type="text" id="fashu-ai-input" placeholder="Hỏi AI chọn cá, làm bể..." style="flex-grow: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none;">
                <button id="fashu-ai-send-btn" type="button" onclick="sendAiMessage()" style="background: #6366f1; color: #fff; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Script điều khiển AI Widget -->
    <script>
        function toggleAiWidget() {
            const popup = document.getElementById("fashu-ai-popup");
            if (popup.style.display === "none" || popup.style.display === "") {
                popup.style.display = "flex";
            } else {
                popup.style.display = "none";
            }
        }

        function openAiModal() {
            const popup = document.getElementById("fashu-ai-popup");
            popup.style.display = "flex";
            document.getElementById("fashu-ai-input").focus();
        }

        function sendQuickAiPrompt(text) {
            document.getElementById("fashu-ai-input").value = text;
            sendAiMessage();
        }

        function sendAiMessage() {
            const input = document.getElementById("fashu-ai-input");
            const message = input.value.trim();
            if (!message) return;

            const chatMessages = document.getElementById("fashu-ai-messages");

            // Append User Message
            chatMessages.innerHTML += `
                <div style="text-align: right;">
                    <div style="display: inline-block; padding: 8px 12px; border-radius: 12px 12px 2px 12px; background: #7c3aed; color: #fff; max-width: 85%; word-break: break-word; text-align: left;">
                        ${escapeHtml(message)}
                    </div>
                </div>
            `;
            input.value = "";
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Indicator Loading
            const loadingId = 'ai-loading-' + Date.now();
            chatMessages.innerHTML += `
                <div id="${loadingId}" style="text-align: left;">
                    <div style="display: inline-block; padding: 8px 12px; border-radius: 12px 12px 12px 2px; background: #f1f5f9; color: #64748b; font-style: italic;">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> AI đang suy nghĩ...
                    </div>
                </div>
            `;
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Gửi tới route AI backend hoặc Giả lập phản hồi AI thông minh
            fetch("{{ route('ai.chat') ?? '/ai/chat' }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById(loadingId).remove();
                appendAiResponse(data.reply || data.message || "Tôi đã nhận câu hỏi của bạn. Hãy tham khảo các dòng cá hiện có tại cửa hàng Fashu Aqua!");
            })
            .catch(() => {
                // Fallback phản hồi giả lập nếu chưa có API backend
                document.getElementById(loadingId).remove();
                let fallbackReply = generateFallbackAiReply(message);
                appendAiResponse(fallbackReply);
            });
        }

        function appendAiResponse(replyText) {
            const chatMessages = document.getElementById("fashu-ai-messages");
            chatMessages.innerHTML += `
                <div style="text-align: left;">
                    <div style="display: inline-block; padding: 8px 12px; border-radius: 12px 12px 12px 2px; background: #e0e7ff; color: #1e1b4b; max-width: 90%; word-break: break-word; line-height: 1.4;">
                        ${replyText}
                    </div>
                </div>
            `;
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function generateFallbackAiReply(msg) {
            const lower = msg.toLowerCase();
            if (lower.includes('bể') || lower.includes('60cm') || lower.includes('kích thước')) {
                return "✨ **Gợi ý cho bể 60cm**: Bạn có thể thả 15-20 cá Neon, 1 cặp cá Phượng Hoàng, 5 cá Chuột dọn bể và một số tép cảnh. Đừng quên trang bị lọc thác hoặc lọc thùng nhé!";
            } else if (lower.includes('chung') || lower.includes('bảy màu') || lower.includes('ghép')) {
                return "🐠 **Cá Bảy Màu (Guppy)** rất hiền lành! Bạn có thể nuôi chung với cá Sọc Ngựa, cá Mây Trắng, cá Mún, Tép Cảnh hoặc cá Trâm mà không lo bị cắn đuôi.";
            } else if (lower.includes('đục') || lower.includes('nước') || lower.includes('vàng')) {
                return "💧 **Xử lý nước đục**: 1. Thay 30% nước sạch. 2. Bổ sung vi sinh sống. 3. Giảm lượng thức ăn dư thừa và kiểm tra hệ thống lọc bọt/bông lọc.";
            }
            return "✨ AI Fashu khuyến nghị: Hãy chọn dòng cá phù hợp với thể tích bể và chỉ số nước pH từ 6.5 - 7.5 để cá luôn khỏe mạnh rực rỡ nhé!";
        }

        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        document.getElementById("fashu-ai-input").addEventListener("keypress", function(e) {
            if (e.key === "Enter") sendAiMessage();
        });
    </script>
    <!-- ==================== END WIDGET AI ==================== -->

    @auth
        @if(Auth::user()->role === 'admin')
            <div id="home-admin-chat-box" style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;">
                <button id="home-admin-chat-toggle" type="button" style="background: #0f172a; color: #fff; padding: 12px 20px; border-radius: 30px; font-weight: bold; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 8px; font-size: 14px;">
                    💬 Hỗ trợ Khách hàng
                </button>

                <div id="home-admin-chat-popup" style="display: none; width: 520px; background: #fff; border: 1px solid #cbd5e1; border-radius: 12px; position: absolute; bottom: 65px; left: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow: hidden;">
                    <div style="background: #0f172a; color: #fff; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="font-size: 14px;">Trung tâm Tư vấn & Hỗ trợ Khách hàng</strong>
                        <button id="home-admin-chat-close" type="button" style="background: #334155; color: #fff; border: none; width: 24px; height: 24px; cursor: pointer; font-weight: bold; border-radius: 50%; display: flex; align-items: center; justify-content: center;">✕</button>
                    </div>
                    <div style="display: flex; height: 340px;">
                        <div id="home-admin-user-list" style="width: 38%; border-right: 1px solid #e2e8f0; overflow-y: auto; background: #f8fafc;">
                            <div style="padding: 12px; font-size: 12px; color: #64748b; text-align: center;">Đang tải danh sách...</div>
                        </div>
                        <div style="width: 62%; display: flex; flex-direction: column;">
                            <div id="home-admin-chat-messages" style="flex-grow: 1; padding: 12px; overflow-y: auto; background: #fff; font-size: 13px;">
                                <div style="text-align: center; margin-top: 100px; color: #94a3b8;"><small>Chọn khách hàng bên trái để bắt đầu chat</small></div>
                            </div>
                            <div style="padding: 10px; border-top: 1px solid #e2e8f0; display: flex; background: #f8fafc;">
                                <input type="text" id="home-admin-chat-input" placeholder="Nhập câu trả lời..." style="flex-grow: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;">
                                <button id="home-admin-send-btn" type="button" style="background: #2563eb; color: #fff; border: none; padding: 8px 16px; margin-left: 6px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let currentUserId = null;
                const homeAdminChatPopup = document.getElementById("home-admin-chat-popup");
                const homeAdminChatMessages = document.getElementById("home-admin-chat-messages");
                const homeAdminChatInput = document.getElementById("home-admin-chat-input");
                const homeAdminSendBtn = document.getElementById("home-admin-send-btn");

                function updateAdminSendState() {
                    const hasSelection = !!currentUserId;
                    homeAdminChatInput.disabled = !hasSelection;
                    homeAdminSendBtn.disabled = !hasSelection;
                    homeAdminChatInput.placeholder = hasSelection ? 'Nhập câu trả lời...' : 'Chọn khách hàng trước để chat';
                }

                document.getElementById("home-admin-chat-toggle").onclick = () => {
                    homeAdminChatPopup.style.display = "block";
                    updateAdminSendState();
                    loadUsers();
                };
                document.getElementById("home-admin-chat-close").onclick = () => {
                    homeAdminChatPopup.style.display = "none";
                };

                function loadUsers() {
                    fetch("{{ route('admin.chat.users') }}")
                        .then(res => res.json())
                        .then(users => {
                            let html = "";
                            users.forEach(user => {
                                let activeClass = (currentUserId == user.id) ? 'background: #2563eb; color: #fff;' : '';
                                html += `<div class="user-item" style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; cursor: pointer; font-size: 13px; font-weight: 500; transition: background 0.2s; ${activeClass}" onclick="selectUser(${user.id}, this)">${user.name}</div>`;
                            });
                            document.getElementById("home-admin-user-list").innerHTML = html || '<div style="padding: 12px; color: #94a3b8; text-align: center; font-size: 12px;"><small>Chưa có hội thoại nào</small></div>';
                            updateAdminSendState();
                        });
                }

                function selectUser(userId, element) {
                    currentUserId = userId;
                    document.querySelectorAll('.user-item').forEach(el => { el.style.background = ''; el.style.color = ''; });
                    element.style.background = '#2563eb';
                    element.style.color = '#fff';
                    updateAdminSendState();
                    loadMessages();
                }

                function loadMessages() {
                    if (!currentUserId) return;
                    fetch(`/admin/chat/messages/${currentUserId}`)
                        .then(res => res.json())
                        .then(messages => {
                            let html = "";
                            messages.forEach(msg => {
                                let senderName = msg.sender_id == "{{ Auth::id() }}" ? "Bạn" : (msg.sender ? msg.sender.name : 'Khách');
                                let isMe = msg.sender_id == "{{ Auth::id() }}";
                                html += `<div style="margin-bottom: 8px; text-align: ${isMe ? 'right' : 'left'};">
                                    <div style="display: inline-block; padding: 6px 10px; border-radius: 8px; background: ${isMe ? '#dbeafe' : '#f1f5f9'}; color: ${isMe ? '#1e40af' : '#334155'}; text-align: left; max-width: 85%; word-break: break-word;">
                                        <strong>${senderName}:</strong> ${msg.content}
                                    </div>
                                </div>`;
                            });
                            homeAdminChatMessages.innerHTML = html;
                            homeAdminChatMessages.scrollTop = homeAdminChatMessages.scrollHeight;
                        });
                }

                function sendMessage() {
                    let message = homeAdminChatInput.value.trim();
                    if (!message) {
                        homeAdminChatInput.focus();
                        return;
                    }
                    if (!currentUserId) {
                        homeAdminChatInput.placeholder = 'Chọn khách hàng trước để chat';
                        homeAdminChatInput.focus();
                        return;
                    }

                    fetch("{{ route('admin.chat.send') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: message, user_id: currentUserId })
                    })
                        .then(res => res.json())
                        .then(() => {
                            homeAdminChatInput.value = "";
                            loadMessages();
                        })
                        .catch(() => {
                            homeAdminChatInput.value = "";
                            homeAdminChatInput.placeholder = 'Không thể gửi. Thử lại.';
                        });
                }

                homeAdminSendBtn.onclick = sendMessage;
                homeAdminChatInput.onkeypress = (e) => { if (e.key === 'Enter' && currentUserId) sendMessage(); };
                updateAdminSendState();

                setInterval(() => {
                    if (homeAdminChatPopup && homeAdminChatPopup.style.display === "block") {
                        loadMessages();
                        loadUsers();
                    }
                }, 3000);
            </script>
        @else
            <div id="home-user-chat-box" style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;">
                <button id="home-user-chat-toggle" type="button" style="background: #007bff; color: #fff; width: 55px; height: 55px; border-radius: 50%; border: none; font-size: 20px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">💬</button>

                <div id="home-user-chat-popup" style="display: none; width: 300px; background: #fff; border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); position: absolute; bottom: 65px; left: 0; overflow: hidden;">
                    <div style="background: #007bff; color: #fff; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: bold; font-size: 14px;">Hỗ trợ khách hàng</span>
                        <button id="home-user-chat-close" type="button" style="background: transparent; border: none; color: #fff; font-weight: bold; cursor: pointer; font-size: 16px;">×</button>
                    </div>
                    <div id="home-user-chat-messages" style="height: 240px; overflow-y: auto; padding: 10px; background: #fdfdfd; font-size: 13px;">
                        <small style="color: #666;">Đang tải lịch sử...</small>
                    </div>
                    <div style="padding: 8px; border-top: 1px solid #ddd; background: #fff; display: flex;">
                        <input type="text" id="home-user-chat-input" placeholder="Nhập tin nhắn..." autocomplete="off" style="flex-grow: 1; padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px; outline: none;">
                        <button id="home-user-send-btn" type="button" style="background: #28a745; color: #fff; border: none; padding: 6px 12px; margin-left: 5px; border-radius: 4px; cursor: pointer; font-size: 12px;">Gửi</button>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const toggleBtn = document.getElementById("home-user-chat-toggle");
                    const chatPopup = document.getElementById("home-user-chat-popup");
                    const closeBtn = document.getElementById("home-user-chat-close");
                    const sendBtn = document.getElementById("home-user-send-btn");
                    const input = document.getElementById("home-user-chat-input");
                    const chatBox = document.getElementById("home-user-chat-messages");

                    if (!toggleBtn) return;

                    toggleBtn.onclick = () => {
                        chatPopup.style.display = "block";
                        toggleBtn.style.display = "none";
                        loadMessages();
                    };
                    closeBtn.onclick = () => {
                        chatPopup.style.display = "none";
                        toggleBtn.style.display = "flex";
                    };

                    function loadMessages() {
                        fetch("{{ route('user.chat.messages') }}")
                            .then(res => res.json())
                            .then(messages => {
                                let html = "";
                                if(messages.length === 0) {
                                    html = "<div style='text-align: center; color: #888; margin-top: 70px;'><small>Bắt đầu cuộc trò chuyện với Admin</small></div>";
                                }
                                messages.forEach(msg => {
                                    const isMe = msg.sender_id == "{{ Auth::id() }}";
                                    html += `
                                    <div style="margin-bottom: 8px; text-align: ${isMe ? 'right' : 'left'};">
                                        <div style="display: inline-block; padding: 6px 10px; border-radius: 8px; background: ${isMe ? '#dcf8c6' : '#f1f0f0'}; text-align: left; max-width: 85%; word-break: break-word;">
                                            <strong>${isMe ? 'Bạn' : 'Admin'}:</strong> ${msg.content}
                                        </div>
                                    </div>
                                    `;
                                });
                                chatBox.innerHTML = html;
                                chatBox.scrollTop = chatBox.scrollHeight;
                            });
                    }

                    function sendMessage() {
                        let message = input.value.trim();
                        if (message === "") return;

                        input.disabled = true;
                        sendBtn.disabled = true;

                        fetch("{{ route('user.chat.send') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                "Content-Type": "application/json",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({ message: message })
                        })
                            .then(res => res.json())
                            .then(() => {
                                input.value = "";
                                input.disabled = false;
                                sendBtn.disabled = false;
                                input.focus();
                                loadMessages();
                            })
                            .catch(() => {
                                input.disabled = false;
                                sendBtn.disabled = false;
                            });
                    }

                    sendBtn.onclick = sendMessage;
                    input.addEventListener("keypress", function(e) {
                        if (e.key === "Enter") { sendMessage(); }
                    });

                    setInterval(() => {
                        if (chatPopup && chatPopup.style.display === "block") {
                            loadMessages();
                        }
                    }, 3000);
                });
            </script>
        @endif
    @endauth

</body>
</html>