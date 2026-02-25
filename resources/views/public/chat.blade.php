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
            --bg-body: #fdfbf7;
            --gold-main: #c5a059;
            --gold-light: #e6cfa3;
            --gold-dark: #9e7f41;
            --navy: #102a43;
            --navy-light: #1e3a5f;
            --text-heading: #2c241b;
            --text-body: #595048;
            --shadow-card: 0 15px 40px rgba(0,0,0,0.08);
            --radius-box: 12px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-body);
            height: 100vh; height: 100dvh;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827z' fill='%23c5a059' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1;
        }
        .chat-layout {
            width: 90%; height: 85vh;
            max-width: 1400px;
            background: #fff;
            border-radius: 20px;
            box-shadow: var(--shadow-card);
            display: grid;
            grid-template-columns: 300px 1fr;
            overflow: hidden;
            border: 1px solid rgba(197,160,89,0.2);
        }
        .sidebar {
            background-color: var(--navy);
            color: #fff;
            display: flex; flex-direction: column;
            border-left: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header {
            height: 70px; padding: 0 20px;
            background: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: space-between;
        }
        .sidebar-title { font-size: 0.95rem; font-weight: 800; color: var(--gold-main); display: flex; align-items: center; gap: 8px; }
        .case-list { flex: 1; overflow-y: auto; padding: 12px 8px; }
        .case-item {
            padding: 12px; margin-bottom: 6px;
            border-radius: var(--radius-box);
            background: rgba(255,255,255,0.03);
            border: 1px solid transparent;
            cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; color: #fff;
        }
        .case-item:hover { background: rgba(255,255,255,0.08); border-color: rgba(197,160,89,0.3); }
        .case-item.active { background: linear-gradient(135deg, var(--gold-main), var(--gold-dark)); color: var(--navy); }
        .case-avatar {
            width: 44px; height: 44px; min-width: 44px;
            border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 1rem; color: #fff; flex-shrink: 0;
        }
        .case-item.active .case-avatar { border-color: var(--navy); color: var(--navy); }
        .case-info { overflow: hidden; }
        .case-info h4 { margin: 0; font-size: 0.88rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .case-info p { margin: 3px 0 0; font-size: 0.72rem; opacity: 0.7; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .unread-dot { width: 8px; height: 8px; background: #e74c3c; border-radius: 50%; margin-right: auto; flex-shrink: 0; }
        .chat-area { display: flex; flex-direction: column; }
        .chat-header {
            height: 70px; padding: 0 25px;
            background: #fff; border-bottom: 1px solid #eee;
            display: flex; justify-content: space-between; align-items: center;
        }
        .lawyer-profile { display: flex; align-items: center; gap: 12px; }
        .lawyer-img {
            width: 46px; height: 46px;
            border-radius: 50% 50% 12px 12px;
            object-fit: cover;
            border: 2px solid var(--gold-main);
            background: #eee;
            display: flex; align-items: center; justify-content: center;
            color: var(--navy); font-weight: bold; font-size: 1rem; flex-shrink: 0;
        }
        .lawyer-details h3 { margin: 0; font-size: 0.95rem; color: var(--navy); font-weight: 800; }
        .lawyer-details span { font-size: 0.72rem; color: var(--gold-dark); font-weight: 600; display: block; margin-top: 2px; }
        .online-dot { display: inline-block; width: 8px; height: 8px; background: #2ecc71; border-radius: 50%; margin-left: 4px; }
        .header-tools { display: flex; gap: 12px; }
        .header-tools a {
            color: #aaa; font-size: 1rem; cursor: pointer; transition: 0.3s;
            padding: 8px; border-radius: 50%; text-decoration: none; display: flex; align-items: center; justify-content: center;
        }
        .header-tools a:hover { color: var(--navy); background: #f5f5f5; }
        .messages {
            flex: 1; padding: 20px; overflow-y: auto;
            display: flex; flex-direction: column; gap: 18px;
            background-image: radial-gradient(#f0f0f0 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .msg-date-divider { text-align: center; font-size: 0.75rem; color: #bbb; margin: 5px 0; }
        .msg-row { display: flex; width: 100%; }
        .msg-row.sent { justify-content: flex-end; }
        .msg-row.received { justify-content: flex-start; }
        .msg-bubble {
            max-width: 72%;
            padding: 13px 18px;
            font-size: 0.88rem;
            line-height: 1.65;
            position: relative;
        }
        .msg-row.received .msg-bubble {
            background: #fff; border: 1px solid #eee;
            border-radius: 15px 2px 15px 15px;
            border-right: 3px solid var(--gold-main);
            color: var(--text-heading);
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }
        .msg-row.sent .msg-bubble {
            background: var(--navy); color: #fff;
            border-radius: 2px 15px 15px 15px;
        }
        .msg-meta { display: block; margin-top: 5px; font-size: 0.65rem; opacity: 0.6; text-align: left; }
        .msg-row.sent .msg-meta { color: var(--gold-main); opacity: 1; }
        .attachment {
            display: flex; align-items: center;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 8px 12px; border-radius: 8px; margin-top: 8px; gap: 10px;
        }
        .received .attachment { background: #f8f8f8; border: 1px solid #ddd; }
        .file-details { display: flex; flex-direction: column; }
        .file-name { font-weight: 700; font-size: 0.78rem; }
        .file-size { font-size: 0.63rem; opacity: 0.8; }
        .chat-footer {
            padding: 14px 20px; background: #fff;
            border-top: 1px solid #e0e0e0;
            display: flex; align-items: center; gap: 10px;
        }
        .input-group {
            flex: 1; position: relative;
            background: #fcfcfc; border-radius: 50px;
            border: 1px solid #e0e0e0;
            display: flex; align-items: center; transition: 0.3s;
        }
        .input-group:focus-within { border-color: var(--gold-main); background: #fff; }
        .msg-input {
            flex: 1; padding: 11px 15px;
            border: none; background: transparent;
            font-family: 'Vazirmatn', sans-serif; font-size: 0.9rem; outline: none;
        }
        .btn-attach { padding: 0 14px; color: #bbb; cursor: pointer; transition: 0.3s; }
        .btn-attach:hover { color: var(--navy); }
        .btn-send {
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--navy); color: var(--gold-main);
            border: none; display: flex; align-items: center; justify-content: center;
            font-size: 1rem; cursor: pointer; transition: 0.3s; flex-shrink: 0;
        }
        .btn-send:hover { background: var(--gold-main); color: #fff; transform: scale(1.05); }
        .empty-chat {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: #ccc; gap: 12px;
        }
        .empty-chat i { font-size: 3rem; }
        .empty-chat p { font-size: 0.9rem; }
        @media (max-width: 1024px) {
            .chat-layout { width: 100%; height: 100vh; border-radius: 0; grid-template-columns: 240px 1fr; border: none; }
        }
        @media (max-width: 768px) {
            .chat-layout { grid-template-columns: 64px 1fr; }
            .sidebar-title span, .case-info, .sidebar-header span { display: none; }
            .sidebar-header { justify-content: center; }
            .case-item { justify-content: center; padding: 10px 5px; }
            .messages { padding: 14px; }
            .msg-bubble { max-width: 85%; font-size: 0.83rem; }
        }
    </style>
</head>
<body>
<div class="chat-layout">

    {{-- Sidebar: لیست مکالمات --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">
                <i class="fas fa-folder-open"></i>
                <span>گفتگوها</span>
            </div>
            <a href="{{ route('dashboard') }}" style="color:rgba(255,255,255,0.4);font-size:0.8rem;text-decoration:none;">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="case-list">
            @foreach($conversations as $conv)
                <a href="{{ route('client.chat.show', $conv->id) }}"
                   class="case-item {{ isset($activeConversation) && $activeConversation->id === $conv->id ? 'active' : '' }}">
                    <div class="case-avatar">
                        {{ mb_substr($conv->lawyer->name ?? 'و', 0, 1) }}
                    </div>
                    <div class="case-info">
                        <h4>{{ $conv->lawyer->name ?? 'وکیل' }}</h4>
                        <p>
                            @if($conv->latestMessage)
                                {{ Str::limit($conv->latestMessage->message, 25) }}
                            @else
                                گفتگو شروع نشده
                            @endif
                        </p>
                    </div>
                    @php $unread = $conv->getUnreadCountFor('user', auth()->id()); @endphp
                    @if($unread > 0)
                        <div class="unread-dot"></div>
                    @endif
                </a>
            @endforeach
        </div>
    </aside>

    {{-- Chat Area --}}
    <main class="chat-area">
        @if(isset($activeConversation))

            <header class="chat-header">
                <div class="lawyer-profile">
                    <div class="lawyer-img">
                        {{ mb_substr($activeConversation->lawyer->name ?? 'و', 0, 1) }}
                    </div>
                    <div class="lawyer-details">
                        <h3>{{ $activeConversation->lawyer->name ?? 'وکیل' }}</h3>
                        <span>
                            <span class="online-dot"></span>
                            {{ $activeConversation->lawyer->license_grade ? 'وکیل پایه ' . $activeConversation->lawyer->license_grade : 'وکیل' }}
                        </span>
                    </div>
                </div>
                <div class="header-tools">
                    @if($activeConversation->lawyer->available_for_call)
                        <a href="#" title="تماس تلفنی"><i class="fas fa-phone-alt"></i></a>
                    @endif
                    <a href="#" title="اطلاعات"><i class="fas fa-ellipsis-v"></i></a>
                </div>
            </header>

            <div class="messages" id="messagesContainer">
                @forelse($messages as $msg)
                    @php $isSent = $msg->sender_type === 'user'; @endphp
                    <div class="msg-row {{ $isSent ? 'sent' : 'received' }}">
                        <div class="msg-bubble">
                            {{ $msg->message }}

                            @if($msg->attachments)
                                @foreach($msg->attachments as $att)
                                    <div class="attachment">
                                        <i class="fas fa-file file-icon" style="color:{{ $isSent ? 'var(--gold-main)' : '#e74c3c' }};font-size:1.1rem;"></i>
                                        <div class="file-details">
                                            <span class="file-name">{{ $att['name'] ?? 'فایل' }}</span>
                                            <span class="file-size">{{ isset($att['size']) ? round($att['size']/1024, 1).' KB' : '' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <span class="msg-meta">
                                {{ $msg->created_at->format('H:i') }}
                                @if($isSent && $msg->is_read)
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
                      style="display:flex;align-items:center;gap:10px;width:100%;"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="message" class="msg-input"
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
                <i class="fas fa-comment-dots"></i>
                <p>یک گفتگو را از لیست انتخاب کنید</p>
            </div>
        @endif
    </main>

</div>

<script>
    // اسکرول به آخر پیام‌ها
    const mc = document.getElementById('messagesContainer');
    if (mc) mc.scrollTop = mc.scrollHeight;

    // نمایش نام فایل انتخاب شده
    document.getElementById('fileInput')?.addEventListener('change', function() {
        const name = this.files[0]?.name;
        if (name) document.querySelector('.msg-input').placeholder = '📎 ' + name;
    });
</script>
</body>
</html>
