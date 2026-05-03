<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>اتاق گفتگو | دفتر وکالت ابدالی و جوشقانی</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-page: #f4f7f6;
            --bg-chat: #ffffff;
            --gold-main: #c5a059;
            --gold-light: #e6cfa3;
            --gold-dark: #9e7f41;
            --navy: #0f2027;
            --navy-light: #203a43;
            --text-main: #2c3e50;
            --text-muted: #7f8c8d;
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 50px rgba(15, 32, 39, 0.15);
            --radius-lg: 24px;
            --radius-md: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, var(--bg-page) 0%, #e2e8e4 100%);
            color: var(--text-main);
            height: 100vh;
            height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        .chat-layout {
            width: 95%;
            max-width: 1300px;
            height: 90vh;
            background: var(--bg-chat);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            display: grid;
            grid-template-columns: 320px 1fr;
            overflow: hidden;
        }

        /* ════════════ SIDEBAR ════════════ */
        .sidebar {
            background: linear-gradient(180deg, var(--navy) 0%, var(--navy-light) 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gold-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .case-list {
            flex: 1;
            overflow-y: auto;
            padding: 15px 10px;
        }

        .case-item {
            padding: 14px;
            margin-bottom: 8px;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .case-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
        }

        .case-item.active {
            background: rgba(197, 160, 89, 0.15);
            border-color: var(--gold-main);
        }

        .case-item.active::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gold-main);
            border-radius: 4px 0 0 4px;
        }

        .case-avatar {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
            box-shadow: 0 4px 10px rgba(197, 160, 89, 0.3);
            position: relative;
        }

        .case-info {
            flex: 1;
            overflow: hidden;
        }

        .case-info h4 {
            margin: 0 0 4px;
            font-size: 0.95rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .case-info p {
            margin: 0;
            font-size: 0.8rem;
            opacity: 0.7;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .unread-badge {
            background: #e74c3c;
            color: #fff;
            font-size: 0.7rem;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(231, 76, 60, 0.4);
        }

        /* ════════════ CHAT AREA ════════════ */
        .chat-area {
            display: flex;
            flex-direction: column;
            background: #fafafa;
        }

        .chat-header {
            padding: 15px 30px;
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            z-index: 10;
        }

        .lawyer-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .lawyer-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--navy);
            color: var(--gold-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .lawyer-details h3 {
            margin: 0 0 4px;
            font-size: 1.05rem;
            color: var(--navy);
            font-weight: 800;
        }

        .lawyer-details span {
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .online-dot {
            width: 8px;
            height: 8px;
            background: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.2);
        }

        .header-tools a {
            color: var(--text-muted);
            font-size: 1.1rem;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
        }

        .header-tools a:hover {
            color: var(--navy);
            background: #f0f0f0;
        }

        /* --- Messages --- */
        .messages {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            background-image: radial-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        @keyframes fadeInMsg {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .msg-row {
            display: flex;
            width: 100%;
            animation: fadeInMsg 0.4s ease forwards;
        }

        .msg-row.sent {
            justify-content: flex-end;
        }

        .msg-row.received {
            justify-content: flex-start;
        }

        .msg-bubble {
            max-width: 65%;
            padding: 14px 20px;
            font-size: 0.95rem;
            line-height: 1.7;
            position: relative;
            box-shadow: var(--shadow-sm);
        }

        .msg-row.received .msg-bubble {
            background: #fff;
            color: var(--text-main);
            border-radius: 20px 20px 2px 20px;
            border-right: 3px solid var(--gold-main);
        }

        .msg-row.sent .msg-bubble {
            background: var(--navy);
            color: #fff;
            border-radius: 20px 20px 20px 2px;
        }

        .msg-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
            margin-top: 8px;
            font-size: 0.7rem;
            opacity: 0.7;
        }

        .msg-row.sent .msg-meta {
            color: var(--gold-light);
            opacity: 1;
        }

        /* --- Attachments --- */
        .attachment {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 14px;
            border-radius: 12px;
            margin-top: 10px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: 0.3s;
        }

        .msg-row.received .attachment {
            background: #f8f9fa;
            border: 1px solid #eee;
        }

        .attachment:hover {
            transform: translateY(-2px);
        }

        .file-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(197, 160, 89, 0.2);
            color: var(--gold-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .msg-row.received .file-icon {
            background: rgba(44, 62, 80, 0.1);
            color: var(--navy);
        }

        .file-details {
            display: flex;
            flex-direction: column;
        }

        .file-name {
            font-weight: 700;
            font-size: 0.85rem;
        }

        .file-size {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        /* --- Footer / Input --- */
        .chat-footer {
            padding: 20px 30px;
            background: #fff;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f4f6f8;
            border-radius: 30px;
            padding: 5px 5px 5px 20px;
            transition: all 0.3s;
            border: 1px solid transparent;
        }

        .input-wrapper:focus-within {
            background: #fff;
            border-color: var(--gold-main);
            box-shadow: 0 5px 15px rgba(197, 160, 89, 0.1);
        }

        .msg-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 0;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.95rem;
            outline: none;
            color: var(--text-main);
        }

        .msg-input::placeholder {
            color: #a0aab5;
        }

        .btn-attach {
            color: #a0aab5;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 10px;
            transition: 0.3s;
        }

        .btn-attach:hover {
            color: var(--gold-main);
            transform: scale(1.1);
        }

        .btn-send {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--gold-main);
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-send:hover {
            background: var(--gold-dark);
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(197, 160, 89, 0.3);
        }

        /* --- Empty State --- */
        .empty-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            gap: 15px;
        }

        .empty-icon-wrap {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(197, 160, 89, 0.1);
            color: var(--gold-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
        }

        /* --- Responsive --- */
        @media (max-width: 1024px) {
            .chat-layout {
                width: 100%;
                height: 100vh;
                border-radius: 0;
                grid-template-columns: 280px 1fr;
            }
        }

        @media (max-width: 768px) {
            .chat-layout {
                grid-template-columns: 80px 1fr;
            }

            .sidebar-title span,
            .case-info,
            .sidebar-header a {
                display: none;
            }

            .sidebar-header {
                justify-content: center;
                padding: 20px 0;
            }

            .case-item {
                justify-content: center;
                padding: 12px;
            }

            .case-avatar {
                width: 42px;
                height: 42px;
            }

            .messages {
                padding: 15px;
            }

            .msg-bubble {
                max-width: 85%;
            }

            .chat-header,
            .chat-footer {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="chat-layout">

        {{-- ════════════ Sidebar ════════════ --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">
                    <i class="fas fa-balance-scale"></i>
                    <span>لیست پرونده‌ها</span>
                </div>
                <a href="{{ route('dashboard.index') }}"
                    style="color:rgba(255,255,255,0.5);font-size:0.9rem;text-decoration:none;transition:0.3s;"
                    onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>

            <div class="case-list">
                @foreach ($conversations as $conv)
                    <a href="{{ route('client.chat.show', $conv->id) }}"
                        class="case-item {{ isset($activeConversation) && $activeConversation->id === $conv->id ? 'active' : '' }}">
                        <div class="case-avatar">
                            {{ mb_substr($conv->lawyer->name ?? 'و', 0, 1) }}
                        </div>
                        <div class="case-info">
                            <h4>{{ $conv->lawyer->name ?? 'وکیل پایه یک' }}</h4>
                            <p>
                                @if ($conv->latestMessage)
                                    {{ Str::limit($conv->latestMessage->message, 30) }}
                                @else
                                    برای شروع پیام دهید...
                                @endif
                            </p>
                        </div>
                        @php $unread = $conv->getUnreadCountFor('user', auth()->id()); @endphp
                        @if ($unread > 0)
                            <span class="unread-badge">{{ $unread }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- ════════════ Chat Main Area ════════════ --}}
        <main class="chat-area">
            @if (isset($activeConversation))

                <header class="chat-header">
                    <div class="lawyer-profile">
                        <div class="lawyer-img">
                            {{ mb_substr($activeConversation->lawyer->name ?? 'و', 0, 1) }}
                        </div>
                        <div class="lawyer-details">
                            <h3>{{ $activeConversation->lawyer->name ?? 'وکیل مشاور' }}</h3>
                            <span>
                                <span class="online-dot"></span>
                                {{ $activeConversation->lawyer->license_grade ? 'وکیل پایه ' . $activeConversation->lawyer->license_grade : 'آماده پاسخگویی' }}
                            </span>
                        </div>
                    </div>
                    <div class="header-tools">
                        @if ($activeConversation->lawyer->available_for_call)
                            <a href="#" title="تماس مستقیم"><i class="fas fa-phone-alt"></i></a>
                        @endif
                        <a href="#" title="جزئیات پرونده"><i class="fas fa-file-contract"></i></a>
                    </div>
                </header>

                <div class="messages" id="messagesContainer">
                    @forelse($messages as $msg)
                        @php $isSent = $msg->sender_type === 'user'; @endphp
                        <div class="msg-row {{ $isSent ? 'sent' : 'received' }}">
                            <div class="msg-bubble">
                                {{ $msg->message }}

                                @if ($msg->attachments)
                                    @foreach ($msg->attachments as $att)
                                        <div class="attachment">
                                            <div class="file-icon"><i class="fas fa-file-pdf"></i></div>
                                            <div class="file-details">
                                                <span class="file-name">{{ $att['name'] ?? 'مدرک_پیوست.pdf' }}</span>
                                                <span
                                                    class="file-size">{{ isset($att['size']) ? round($att['size'] / 1024, 1) . ' KB' : 'فایل سند' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <span class="msg-meta">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if ($isSent)
                                        <i class="fas {{ $msg->is_read ? 'fa-check-double' : 'fa-check' }}"></i>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-chat" style="height: 100%;">
                            <div class="empty-icon-wrap"><i class="fas fa-hand-sparkles"></i></div>
                            <h3 style="color:var(--navy); font-weight:800;">به اتاق گفتگو خوش آمدید</h3>
                            <p>سؤالات حقوقی یا مدارک خود را در اینجا ارسال کنید.</p>
                        </div>
                    @endforelse
                </div>

                <footer class="chat-footer">
                    <form method="POST" action="{{ route('client.chat.send', $activeConversation->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="input-wrapper">
                            <label for="fileInput" class="btn-attach" title="ارسال مدرک/فایل">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <input type="file" id="fileInput" name="attachment" style="display:none;"
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">

                            <input type="text" name="message" class="msg-input"
                                placeholder="پیام خود را تایپ کنید..." autocomplete="off">

                            <button type="submit" class="btn-send">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </footer>
            @else
                <div class="empty-chat">
                    <div class="empty-icon-wrap"><i class="fas fa-comments"></i></div>
                    <h3 style="color:var(--navy); font-weight:800;">اتاق گفتگو</h3>
                    <p>لطفاً برای مشاهده پیام‌ها، یک پرونده را از منوی کناری انتخاب کنید.</p>
                </div>
            @endif
        </main>

    </div>

    <script>
        // اسکرول نرم به پایین‌ترین پیام هنگام لود صفحه
        document.addEventListener("DOMContentLoaded", function() {
            const mc = document.getElementById('messagesContainer');
            if (mc) {
                mc.scrollTop = mc.scrollHeight;
            }
        });

        // تغییر دیزاین input در صورت انتخاب فایل
        document.getElementById('fileInput')?.addEventListener('change', function() {
            const name = this.files[0]?.name;
            if (name) {
                const inputField = document.querySelector('.msg-input');
                inputField.placeholder = '📎 فایل آماده ارسال: ' + name;
                inputField.style.color = 'var(--gold-dark)';
                inputField.style.fontWeight = 'bold';
            }
        });
    </script>

</body>

</html>
