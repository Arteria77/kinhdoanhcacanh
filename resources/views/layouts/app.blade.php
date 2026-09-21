<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Nút nổi cố định góc dưới bên phải (Admin hoặc Giỏ hàng) -->
            @php
                $isAdmin = auth()->check() && (optional(auth()->user())->role === 'admin' || optional(auth()->user())->is_admin == 1);
            @endphp

            @if($isAdmin)
                <!-- Nút dành riêng cho Admin: Chuyển đến trang Quản lý đơn hàng -->
                <a href="{{ route('admin.orders.index') }}" class="fixed bottom-6 right-6 bg-purple-600 hover:bg-purple-700 text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-2xl z-50 transition duration-200 group" title="Quản lý đơn hàng">
                    📋
                    <span class="absolute -top-2 -right-2 bg-indigo-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md whitespace-nowrap">
                        Admin
                    </span>
                </a>
            @else
                <!-- Nút dành cho Khách hàng: Xem giỏ hàng -->
                <a href="{{ route('cart.index') }}" class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-2xl z-50 transition duration-200 group" title="Xem giỏ hàng">
                    🛒
                    @php
                        $cartCount = session('cart') ? count(session('cart')) : 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-md">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            @endif


            <!-- ========================================== -->
            <!-- TÍCH HỢP KHUNG LIVE CHAT Ở GÓC DƯỚI BÊN TRÁI -->
            <!-- ========================================== -->
            @auth
                @if($isAdmin)
                    <!-- GIAO DIỆN CHAT DÀNH CHO ADMIN -->
                    <div id="app-admin-chat-box" style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;"> 
                        <button id="app-admin-chat-toggle" type="button" style="background: #111; color: #fff; padding: 12px 20px; border-radius: 30px; font-weight: bold; border: none; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">💬 Chat Khách hàng</button> 
                        
                        <div id="app-admin-chat-popup" style="display: none; width: 480px; background: #fff; border: 1px solid #ddd; border-radius: 8px; position: absolute; bottom: 60px; left: 0; box-shadow: 0 5px 15px rgba(0,0,0,0.3); overflow: hidden;"> 
                            <div style="background: #222; color: #fff; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;"> 
                                <strong style="font-size: 14px;">Hỗ trợ trực tuyến (Admin)</strong> 
                                <button id="app-admin-chat-close" type="button" style="background: #fff; color: #000; border: none; padding: 2px 8px; cursor: pointer; font-weight: bold; border-radius: 4px;">X</button> 
                            </div> 
                            <div style="display: flex; height: 300px;">
                                <!-- Danh sách User -->
                                <div id="app-admin-user-list" style="width: 35%; border-right: 1px solid #ddd; overflow-y: auto; background: #f8f9fa;"> 
                                    <div style="padding: 10px; font-size: 12px; color: #666; text-align: center;">Đang tải danh sách...</div> 
                                </div> 
                                <!-- Khung tin nhắn & ô nhập -->
                                <div style="width: 65%; display: flex; flex-direction: column;">
                                    <div id="app-admin-chat-messages" style="flex-grow: 1; padding: 10px; overflow-y: auto; background: #fff; font-size: 13px;"> 
                                        <div style="text-align: center; margin-top: 80px; color: #888;"><small>Chọn khách hàng để xem tin</small></div> 
                                    </div> 
                                    <div style="padding: 8px; border-top: 1px solid #ddd; display: flex; background: #f9f9f9;"> 
                                        <input type="text" id="app-admin-chat-input" placeholder="Nhập câu trả lời..." style="flex-grow: 1; padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px; outline: none;"> 
                                        <button id="app-admin-send-btn" type="button" style="background: #28a745; color: #fff; border: none; padding: 6px 12px; margin-left: 5px; border-radius: 4px; cursor: pointer; font-size: 12px;">Gửi</button> 
                                    </div> 
                                </div>
                            </div>
                        </div> 
                    </div>
                @else
                    <!-- GIAO DIỆN CHAT DÀNH CHO USER -->
                    <div id="app-user-chat-box" style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;"> 
                        <button id="app-user-chat-toggle" type="button" style="background: #007bff; color: #fff; width: 55px; height: 55px; border-radius: 50%; border: none; font-size: 20px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">💬</button> 
                        
                        <div id="app-user-chat-popup" style="display: none; width: 300px; background: #fff; border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); position: absolute; bottom: 65px; left: 0; overflow: hidden;"> 
                            <div style="background: #007bff; color: #fff; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;"> 
                                <span style="font-weight: bold; font-size: 14px;">Hỗ trợ khách hàng</span> 
                                <button id="app-user-chat-close" type="button" style="background: transparent; border: none; color: #fff; font-weight: bold; cursor: pointer; font-size: 16px;">×</button> 
                            </div> 
                            <div id="app-user-chat-messages" style="height: 240px; overflow-y: auto; padding: 10px; background: #fdfdfd; font-size: 13px;"> 
                                <small style="color: #666;">Đang tải lịch sử...</small> 
                            </div> 
                            <div style="padding: 8px; border-top: 1px solid #ddd; background: #fff; display: flex;"> 
                                <input type="text" id="app-user-chat-input" placeholder="Nhập tin nhắn..." autocomplete="off" style="flex-grow: 1; padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px; outline: none;"> 
                                <button id="app-user-send-btn" type="button" style="background: #28a745; color: #fff; border: none; padding: 6px 12px; margin-left: 5px; border-radius: 4px; cursor: pointer; font-size: 12px;">Gửi</button> 
                            </div> 
                        </div> 
                    </div>
                @endif
            @endauth

        </div>

        <!-- SCRIPTS XỬ LÝ CHAT REALTIME -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @auth
            @if($isAdmin)
                <!-- Script Admin Chat -->
                <script>
                let appCurrentUserId = null;
                const appAdminChatPopup = document.getElementById("app-admin-chat-popup");
                const appAdminChatMessages = document.getElementById("app-admin-chat-messages");
                const appAdminChatInput = document.getElementById("app-admin-chat-input");

                document.getElementById("app-admin-chat-toggle").onclick = () => {
                    appAdminChatPopup.style.display = "block";
                    appLoadUsers();
                };
                document.getElementById("app-admin-chat-close").onclick = () => {
                    appAdminChatPopup.style.display = "none";
                };

                function appLoadUsers() {
                    fetch("{{ route('admin.chat.users') }}")
                    .then(res => res.json())
                    .then(users => {
                        let html = "";
                        users.forEach(user => {
                            let activeClass = (appCurrentUserId == user.id) ? 'background: #007bff; color: #fff;' : '';
                            html += `<div class="user-item" style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; font-size: 13px; ${activeClass}" onclick="appSelectUser(${user.id}, this)">${user.name}</div>`;
                        });
                        document.getElementById("app-admin-user-list").innerHTML = html || '<div style="padding: 10px; color: #888; text-align: center; font-size: 12px;"><small>Chưa có hội thoại</small></div>';
                    });
                }

                function appSelectUser(userId, element) {
                    appCurrentUserId = userId;
                    document.querySelectorAll('.user-item').forEach(el => { el.style.background = ''; el.style.color = ''; });
                    element.style.background = '#007bff';
                    element.style.color = '#fff';
                    appLoadMessages();
                }

                function appLoadMessages() {
                    if (!appCurrentUserId) return;
                    fetch(`/admin/chat/messages/${appCurrentUserId}`)
                    .then(res => res.json())
                    .then(messages => {
                        let html = "";
                        messages.forEach(msg => {
                            let senderName = msg.sender_id == "{{ Auth::id() }}" ? "Bạn" : (msg.sender ? msg.sender.name : 'Khách');
                            let isMe = msg.sender_id == "{{ Auth::id() }}";
                            html += `<div style="margin-bottom: 8px; text-align: ${isMe ? 'right' : 'left'};">
                                <div style="display: inline-block; padding: 6px 10px; border-radius: 8px; background: ${isMe ? '#dcf8c6' : '#f1f0f0'}; text-align: left; max-width: 85%; word-break: break-word;">
                                    <strong>${senderName}:</strong> ${msg.content}
                                </div>
                            </div>`;
                        });
                        appAdminChatMessages.innerHTML = html;
                        appAdminChatMessages.scrollTop = appAdminChatMessages.scrollHeight;
                    });
                }

                function appSendMessage() {
                    let message = appAdminChatInput.value.trim();
                    if (!message || !appCurrentUserId) return;

                    fetch("{{ route('admin.chat.send') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: message, user_id: appCurrentUserId })
                    })
                    .then(res => res.json())
                    .then(() => {
                        appAdminChatInput.value = "";
                        appLoadMessages();
                    });
                }

                document.getElementById("app-admin-send-btn").onclick = appSendMessage;
                appAdminChatInput.onkeypress = (e) => { if(e.key === 'Enter') appSendMessage(); };

                setInterval(() => {
                    if (appAdminChatPopup && appAdminChatPopup.style.display === "block") {
                        appLoadMessages();
                        appLoadUsers();
                    }
                }, 3000);
                </script>
            @else
                <!-- Script User Chat -->
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const toggleBtn = document.getElementById("app-user-chat-toggle");
                    const chatPopup = document.getElementById("app-user-chat-popup");
                    const closeBtn = document.getElementById("app-user-chat-close");
                    const sendBtn = document.getElementById("app-user-send-btn");
                    const input = document.getElementById("app-user-chat-input");
                    const chatBox = document.getElementById("app-user-chat-messages");
                    
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