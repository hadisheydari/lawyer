<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>اتاق گفتگو | دفتر وکالت ابدالی و جوشقانی</title>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        .new-chat-btn {
            background: rgba(197, 160, 89, 0.1);
            border-color: var(--gold-main);
            color: var(--gold-main);
            justify-content: center;
            font-weight: bold;
        }

        .new-chat-btn:hover {
            background: rgba(197, 160, 89, 0.2);
            color: #fff;
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
            padding: 20px;
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

        /* استایل اختصاصی فرم ایجاد گفتگو */
        .new-chat-form {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 30px;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(197, 160, 89, 0.2);
            margin-top: 10px;
        }

        .form-select {
            width: 100%;
            padding: 14px 15px;
            border-radius: var(--radius-md);
            border: 2px solid #edf2f7;
            background: #f8fafc;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            outline: none;
            transition: 0.3s;
            margin-bottom: 20px;
        }

        .form-select:focus {
            border-color: var(--gold-main);
            background: #fff;
        }

        .form-btn {
            width: 100%;
            padding: 14px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            border: none;
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(197, 160, 89, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .form-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(197, 160, 89, 0.4);
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

            .new-chat-btn span {
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

        /* =====================================
   TABLET
===================================== */

        @media (max-width:1024px) {

            .chat-layout {
                width: 100%;
                height: 100dvh;
                border-radius: 0;
                grid-template-columns: 260px 1fr;
            }

            .messages {
                padding: 20px;
            }

            .chat-header,
            .chat-footer {
                padding: 15px 20px;
            }

            .msg-bubble {
                max-width: 80%;
            }
        }


        /* =====================================
   MOBILE
===================================== */

        @media (max-width:768px) {

            body {
                background: #fff;
                overflow: hidden;
            }

            .chat-layout {
                width: 100%;
                height: 100dvh;
                border-radius: 0;
                display: flex;
                flex-direction: column;
            }

            /* sidebar */

            .sidebar {
                width: 100%;
                height: 120px;
                min-height: 120px;
                border: none;
            }

            .sidebar-header {
                padding: 12px 15px;
            }

            .sidebar-title {
                font-size: .95rem;
            }

            .case-list {
                display: flex;
                overflow-x: auto;
                overflow-y: hidden;
                gap: 10px;
                padding: 10px;
            }

            .case-item {
                min-width: 85px;
                width: 85px;
                margin: 0;
                padding: 10px;
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }

            .case-avatar {
                width: 42px;
                height: 42px;
                min-width: 42px;
                font-size: .9rem;
            }

            .case-info h4 {
                font-size: .72rem;
                white-space: normal;
                line-height: 1.4;
            }

            .case-info p {
                display: none;
            }

            .unread-badge {
                position: absolute;
                top: 5px;
                left: 5px;
            }

            .new-chat-btn {
                min-width: 85px;
            }

            /* chat */

            .chat-area {
                flex: 1;
                min-height: 0;
            }

            .chat-header {
                padding: 10px 12px;
            }

            .lawyer-profile {
                gap: 10px;
            }

            .lawyer-img {
                width: 40px;
                height: 40px;
                font-size: .95rem;
            }

            .lawyer-details h3 {
                font-size: .9rem;
            }

            .lawyer-details span {
                font-size: .72rem;
            }

            .header-tools a {
                padding: 8px;
                font-size: 1rem;
            }

            .messages {
                padding: 12px;
                gap: 12px;
            }

            .msg-bubble {
                max-width: 95%;
                padding: 12px;
                font-size: .9rem;
                line-height: 1.8;
            }

            .attachment {
                padding: 8px;
            }

            .file-icon {
                width: 34px;
                height: 34px;
                font-size: 1rem;
            }

            .file-name {
                font-size: .8rem;
            }

            .file-size {
                font-size: .65rem;
            }

            /* footer */

            .chat-footer {
                padding: 8px;
            }

            .input-wrapper {
                padding: 4px 4px 4px 10px;
                gap: 4px;
            }

            .msg-input {
                font-size: 16px;
            }

            .btn-attach {
                padding: 8px;
                font-size: 1rem;
            }

            .btn-send {
                width: 42px;
                height: 42px;
                min-width: 42px;
            }

            /* empty state */

            .empty-icon-wrap {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .empty-chat h3 {
                font-size: 1rem !important;
                text-align: center;
            }

            .empty-chat p {
                text-align: center;
                font-size: .85rem;
            }

            .new-chat-form {
                padding: 20px;
                width: 100%;
                max-width: none;
            }
        }


        /* =====================================
   VERY SMALL PHONES
===================================== */

        @media (max-width:480px) {

            .sidebar {
                height: 105px;
                min-height: 105px;
            }

            .case-item {
                min-width: 75px;
                width: 75px;
                padding: 8px;
            }

            .case-avatar {
                width: 36px;
                height: 36px;
                min-width: 36px;
            }

            .case-info h4 {
                font-size: .65rem;
            }

            .messages {
                padding: 8px;
            }

            .msg-bubble {
                max-width: 98%;
                font-size: .85rem;
            }

            .lawyer-details h3 {
                font-size: .82rem;
            }

            .lawyer-details span {
                font-size: .68rem;
            }

        }

        body,
        .chat-layout,
        .msg-input,
        .msg-bubble {
            font-family: 'Vazir', Tahoma, sans-serif !important;
        }

        .file-preview-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff7e6;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 8px 14px;
            margin: 0 0 8px;
            font-size: 0.8rem;
            color: #92400e;
        }

        .file-preview-bar button {
            margin-right: auto;
            background: none;
            border: none;
            color: #b45309;
            cursor: pointer;
        }

        .chat-area {
            min-height: 0;
        }

        .messages {
            min-height: 0;
        }

        .chat-footer form,
        .input-group {
            box-sizing: border-box;
        }

        .msg-input {
            min-width: 0;
        }


        .msg-bubble {
            position: relative;
        }

        .msg-delete-btn {
            position: absolute;
            top: 4px;
            opacity: 0;
            background: rgba(0, 0, 0, 0.15);
            border: none;
            color: inherit;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 0.7rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .msg-row.sent .msg-delete-btn {
            left: -26px;
        }

        .msg-row.received .msg-delete-btn {
            display: none;
        }

        .msg-bubble:hover .msg-delete-btn {
            opacity: 1;
        }

        .msg-delete-btn:hover {
            background: #ef4444;
            color: #fff;
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
                <a href="{{ route('client.chat.index') }}" class="case-item new-chat-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>ایجاد گفتگوی جدید</span>
                </a>

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
                        <div class="msg-row {{ $isSent ? 'sent' : 'received' }}"
                            data-message-id="{{ $msg->id }}">
                            <div class="msg-bubble">
                                @if ($isSent)
                                    <button type="button" class="msg-delete-btn"
                                        onclick="deleteMessage({{ $msg->id }})" title="حذف پیام">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif

                                {{ $msg->message }}

                                @if ($msg->attachments)
                                    @foreach ($msg->attachments as $att)
                                        <div class="attachment">
                                            <i class="fas fa-file file-icon"
                                                style="color:{{ $isSent ? 'var(--gold-main)' : '#e74c3c' }};font-size:1.1rem;"></i>
                                            <div class="file-details">
                                                <a href="{{ route('client.chat.download', [$activeConversation->id, $msg->id]) }}"
                                                    class="file-name" style="color:inherit;text-decoration:underline;">
                                                    {{ $att['name'] ?? 'فایل' }}
                                                </a>
                                                <span
                                                    class="file-size">{{ isset($att['size']) ? round($att['size'] / 1024, 1) . ' KB' : '' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <span class="msg-meta">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if ($isSent && $msg->is_read)
                                        <i class="fas fa-check-double" style="margin-right:4px;"></i>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;color:#ccc;margin:auto;">
                            <i class="fas fa-comments" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            گفتگو را شروع کنید
                        </div>
                    @endforelse
                </div>

                <footer class="chat-footer">
                    <form method="POST" action="{{ route('client.chat.send', $activeConversation->id) }}"
                        id="clientChatForm" style="display:flex;align-items:center;gap:10px;width:100%;"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" id="clientMsgInput" class="msg-input"
                                placeholder="پیام خود را بنویسید..." autocomplete="off">
                            <label for="fileInput" class="btn-attach" title="ارسال فایل">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <input type="file" id="fileInput" name="attachment" style="display:none;"
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        </div>
                        <button type="submit" class="btn-send">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </footer>
            @else
                <div class="empty-chat">
                    <div class="empty-icon-wrap"><i class="fas fa-balance-scale"></i></div>
                    <h3 style="color:var(--navy); font-weight:800; font-size: 1.4rem;">مشاوره و گفتگوی جدید</h3>
                    <p>جهت شروع مکالمه، وکیل متخصص پرونده خود را انتخاب نمایید.</p>

                    <form action="{{ route('client.chat.store') }}" method="POST" class="new-chat-form">
                        @csrf
                        <select name="lawyer_id" class="form-select" required>
                            <option value="" disabled selected>انتخاب وکیل پایه یک...</option>
                            @foreach ($lawyers as $lawyer)
                                <option value="{{ $lawyer->id }}">{{ $lawyer->name }} -
                                    {{ $lawyer->title ?? 'وکیل پایه یک دادگستری' }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="form-btn">
                            <i class="fas fa-comment-dots"></i> شروع مکالمه
                        </button>

                        @error('lawyer_id')
                            <p style="color: #e74c3c; font-size: 0.85rem; margin-top: 10px; text-align: center;">
                                {{ $message }}</p>
                        @enderror
                    </form>
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
        const fileInput = document.getElementById('fileInput');
        const previewBar = document.getElementById('filePreviewBar');
        const previewName = document.getElementById('filePreviewName');

        fileInput?.addEventListener('change', function() {
            if (this.files[0]) {
                previewName.textContent = this.files[0].name;
                previewBar.style.display = 'flex';
            }
        });

        document.getElementById('fileRemoveBtn')?.addEventListener('click', () => {
            fileInput.value = '';
            previewBar.style.display = 'none';
            document.querySelector('.msg-input').placeholder = 'پیام خود را تایپ کنید...';
            document.querySelector('.msg-input').style.color = '';
        });

        document.getElementById('clientChatForm')?.addEventListener('submit', function(e) {
            const msg = document.getElementById('clientMsgInput')?.value.trim() || '';
            const hasFile = document.getElementById('fileInput')?.files.length > 0;
            if (!msg && !hasFile) {
                e.preventDefault();
                alert('لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.');
            }
        });

        function deleteMessage(messageId) {
            if (!confirm('این پیام حذف شود؟')) return;

            const conversationId = @json($activeConversation->id ?? null);
            if (!conversationId) return;

            fetch(`/client/chat/${conversationId}/message/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`[data-message-id="${messageId}"]`);
                        if (row) {
                            row.style.transition = '0.3s';
                            row.style.opacity = '0';
                            setTimeout(() => row.remove(), 300);
                        }
                    } else {
                        alert(data.message || 'خطا در حذف پیام.');
                    }
                })
                .catch(() => alert('خطا در ارتباط با سرور.'));
        }
    </script>

</body>

</html>
