<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị hệ thống') - Cá Cảnh Fashu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    <!-- Header Admin -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xl shadow-md shadow-blue-500/20">
                        🐠
                    </div>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg text-slate-900 tracking-tight hover:text-blue-600 transition">
                            Fashu <span class="text-blue-600">Admin</span>
                        </a>
                        <p class="text-xs text-slate-400 font-medium">Hệ thống quản lý kinh doanh cá cảnh</p>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-100 transition border border-slate-200">
                        <span>🌐</span> Xem Website
                    </a>

                    <div class="flex items-center gap-2.5 pl-3 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-semibold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Quản trị viên' }}</p>
                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase">Admin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Navigation Tabs -->
            <nav class="flex space-x-1 sm:space-x-2 overflow-x-auto py-2 border-t border-slate-100 text-sm no-scrollbar">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.index') ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>📊</span> Doanh thu & Tổng quan
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.categories.*') ? 'bg-cyan-600 text-white shadow-sm shadow-cyan-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>🐟</span> Danh mục loài cá
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.products.*') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>🐠</span> Quản lý cá cảnh
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>📋</span> Quản lý đơn hàng
                </a>

                <a href="{{ route('admin.coupons.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.coupons.*') ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>🎟️</span> Mã giảm giá
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-purple-600 text-white shadow-sm shadow-purple-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>👥</span> Khách hàng
                </a>
            </nav>
        </div>
    </header>

    <!-- Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs animate-fade-in">
                <span class="text-xl">✅</span>
                <div class="font-medium text-sm">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-xs animate-fade-in">
                <span class="text-xl">⚠️</span>
                <div class="font-medium text-sm">{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- ========================================== -->
    <!-- KHUNG CHAT TRỰC TUYẾN DÀNH CHO ADMIN (GÓC TRÁI) -->
    <!-- ========================================== -->
    <div id="admin-dashboard-chat-box" style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;"> 
        <button id="admin-dashboard-chat-toggle" type="button" style="background: #0f172a; color: #fff; padding: 12px 20px; border-radius: 30px; font-weight: bold; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 8px; font-size: 14px;">
            💬 Hỗ trợ Khách hàng
        </button> 
        
        <div id="admin-dashboard-chat-popup" style="display: none; width: 520px; background: #fff; border: 1px solid #cbd5e1; border-radius: 12px; position: absolute; bottom: 65px; left: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow: hidden;"> 
            <div style="background: #0f172a; color: #fff; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;"> 
                <strong style="font-size: 14px;">Trung tâm Tư vấn & Hỗ trợ Khách hàng</strong> 
                <button id="admin-dashboard-chat-close" type="button" style="background: #334155; color: #fff; border: none; width: 24px; height: 24px; cursor: pointer; font-weight: bold; border-radius: 50%; display: flex; align-items: center; justify-content: center;">✕</button> 
            </div> 
            <div style="display: flex; height: 340px;">
                <!-- Cột trái: Danh sách User -->
                <div id="admin-dashboard-user-list" style="width: 38%; border-right: 1px solid #e2e8f0; overflow-y: auto; background: #f8fafc;"> 
                    <div style="padding: 12px; font-size: 12px; color: #64748b; text-align: center;">Đang tải danh sách...</div> 
                </div> 
                <!-- Cột phải: Khung tin nhắn & ô nhập -->
                <div style="width: 62%; display: flex; flex-direction: column;">
                    <div id="admin-dashboard-chat-messages" style="flex-grow: 1; padding: 12px; overflow-y: auto; background: #fff; font-size: 13px;"> 
                        <div style="text-align: center; margin-top: 100px; color: #94a3b8;"><small>Chọn khách hàng bên trái để bắt đầu chat</small></div> 
                    </div> 
                    <div style="padding: 10px; border-top: 1px solid #e2e8f0; display: flex; background: #f8fafc;"> 
                        <input type="text" id="admin-dashboard-chat-input" placeholder="Nhập câu trả lời..." style="flex-grow: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;"> 
                        <button id="admin-dashboard-send-btn" type="button" style="background: #2563eb; color: #fff; border: none; padding: 8px 16px; margin-left: 6px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">Gửi</button> 
                    </div> 
                </div>
            </div>
        </div> 
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Cá Cảnh Fashu. Hệ thống quản trị kinh doanh cá cảnh & Tích hợp vận chuyển GHN.
        </div>
    </footer>

    <!-- SCRIPT XỬ LÝ CHAT ADMIN REALTIME -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    let currentUserId = null;
    const adminDashboardChatPopup = document.getElementById("admin-dashboard-chat-popup");
    const adminDashboardChatMessages = document.getElementById("admin-dashboard-chat-messages");
    const adminDashboardChatInput = document.getElementById("admin-dashboard-chat-input");

    document.getElementById("admin-dashboard-chat-toggle").onclick = () => {
        adminDashboardChatPopup.style.display = "block";
        loadUsers();
    };
    document.getElementById("admin-dashboard-chat-close").onclick = () => {
        adminDashboardChatPopup.style.display = "none";
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
            document.getElementById("admin-dashboard-user-list").innerHTML = html || '<div style="padding: 12px; color: #94a3b8; text-align: center; font-size: 12px;"><small>Chưa có hội thoại nào</small></div>';
        });
    }

    function selectUser(userId, element) {
        currentUserId = userId;
        document.querySelectorAll('.user-item').forEach(el => { el.style.background = ''; el.style.color = ''; });
        element.style.background = '#2563eb';
        element.style.color = '#fff';
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
            adminDashboardChatMessages.innerHTML = html;
            adminDashboardChatMessages.scrollTop = adminDashboardChatMessages.scrollHeight;
        });
    }

    function updateAdminDashboardSendState() {
        const hasSelection = !!currentUserId;
        adminDashboardChatInput.disabled = !hasSelection;
        document.getElementById("admin-dashboard-send-btn").disabled = !hasSelection;
        adminDashboardChatInput.placeholder = hasSelection ? 'Nhập câu trả lời...' : 'Chọn khách hàng trước để chat';
    }

    function sendMessage() {
        let message = adminDashboardChatInput.value.trim();
        if (!message) {
            adminDashboardChatInput.focus();
            return;
        }
        if (!currentUserId) {
            adminDashboardChatInput.placeholder = 'Chọn khách hàng trước để chat';
            adminDashboardChatInput.focus();
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
            adminDashboardChatInput.value = "";
            loadMessages();
        })
        .catch(() => {
            adminDashboardChatInput.value = "";
            adminDashboardChatInput.placeholder = 'Không thể gửi. Thử lại.';
        });
    }

    document.getElementById("admin-dashboard-send-btn").onclick = sendMessage;
    adminDashboardChatInput.onkeypress = (e) => { if(e.key === 'Enter' && currentUserId) sendMessage(); };
    updateAdminDashboardSendState();

    // Tự động làm mới danh sách khách và tin nhắn mỗi 3 giây
    setInterval(() => {
        if (adminDashboardChatPopup && adminDashboardChatPopup.style.display === "block") {
            loadMessages();
            loadUsers();
        }
    }, 3000);
    </script>
</body>
</html>