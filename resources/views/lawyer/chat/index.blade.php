@extends('layouts.lawyer')
@section('title', 'مرکز گفتگو')

@push('styles')
    <style>
        .chat-wrap {
            display: grid;
            grid-template-columns: 300px 1fr;
            height: calc(100vh - 130px);
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        /* ─── سایدبار ─── */
        .chat-sidebar {
            border-left: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
        }

        .cs-header {
            padding: 18px 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .cs-header h3 {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--navy);
            margin: 0 0 10px;
        }

        .cs-search {
            position: relative;
        }

        .cs-search input {
            width: 100%;
            padding: 8px 14px 8px 36px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.82rem;
            outline: none;
        }

        .cs-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .conv-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .conv-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
            color: inherit;
            margin-bottom: 4px;
        }

        .conv-item:hover {
            background: #f8fafc;
        }

        .conv-item.active {
            background: rgba(212, 175, 55, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .conv-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--navy), #1e3a5f);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            color: var(--gold-main);
        }

        .conv-info {
            flex: 1;
            min-width: 0;
        }

        .conv-info h4 {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0 0 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conv-info p {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conv-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .conv-time {
            font-size: 0.68rem;
            color: #94a3b8;
            white-space: nowrap;
        }

        .unread-badge {
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 10px;
        }

        /* ─── ناحیه چت ─── */
        .chat-main {
            display: flex;
            flex-direction: column;
            background: #fafafa;
        }

        .chat-head {
            padding: 14px 22px;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ch-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* دکمه بازگشت مخصوص موبایل */
        .mobile-back {
            display: none;
            background: #f1f5f9;
            color: var(--navy);
            padding: 6px 10px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1rem;
        }

        .ch-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--navy);
            color: var(--gold-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .ch-info h3 {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--navy);
            margin: 0 0 2px;
        }

        .ch-info span {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .online-dot {
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-left: 4px;
        }

        .ch-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .ch-btn {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            font-family: 'Vazirmatn', sans-serif;
            transition: 0.2s;
            white-space: nowrap;
        }

        .ch-btn.close {
            background: #fee2e2;
            color: #b91c1c;
        }

        .ch-btn.close:hover {
            background: #b91c1c;
            color: #fff;
        }

        .ch-btn.reopen {
            background: #d1fae5;
            color: #065f46;
        }

        .ch-btn.reopen:hover {
            background: #065f46;
            color: #fff;
        }

        /* ─── استایل نهایی و استاندارد پیام‌ها ─── */
        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .msg-row {
            display: flex;
            width: 100%;
        }

        /* پیام وکیل (خودت) - چسبیده به سمت راست */
        .msg-row.from-lawyer {
            justify-content: flex-start;
        }

        /* پیام موکل (طرف مقابل) - چسبیده به سمت چپ */
        .msg-row.from-client {
            justify-content: flex-end;
        }

        .msg-bubble {
            max-width: 85%;
            width: fit-content;
            min-width: 85px;
            padding: 8px 14px 6px 14px;
            font-size: 0.9rem;
            line-height: 1.5;
            position: relative;
            display: flex;
            flex-direction: column;
            word-break: break-word;
        }

        /* حباب وکیل (سمت راست) */
        .msg-row.from-lawyer .msg-bubble {
            background: linear-gradient(135deg, var(--navy), #1e3a5f);
            color: #fff;
            /* گوشه پایین-راست تیز (ترتیب: بالاچپ، بالاراست، پایین‌راست، پایین‌چپ) */
            border-radius: 16px 16px 0px 16px;
        }

        /* حباب موکل (سمت چپ) */
        .msg-row.from-client .msg-bubble {
            background: #fff;
            color: #1e293b;
            /* گوشه پایین-چپ تیز */
            border-radius: 16px 16px 16px 0px;
            /* نوار طلایی هم آوردم سمت چپ تا با جهت حباب هماهنگ بشه */
            border-left: 3px solid var(--gold-main);
            border-right: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .msg-meta {
            font-size: 0.65rem;
            margin-top: 2px;
            opacity: 0.7;
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: flex-end;
        }

        .msg-row.from-lawyer .msg-meta {
            color: rgba(255, 255, 255, 0.7);
        }

        .chat-footer {
            padding: 16px 22px;
            background: #fff;
            border-top: 1px solid #f1f5f9;
        }

        .cf-form {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border-radius: 26px;
            padding: 6px 6px 6px 16px;
            border: 1px solid transparent;
            transition: 0.3s;
        }

        .cf-form:focus-within {
            background: #fff;
            border-color: var(--gold-main);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.1);
        }

        .cf-attach {
            color: #94a3b8;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 8px;
            transition: 0.2s;
        }

        .cf-attach:hover {
            color: var(--gold-main);
        }

        .cf-input {
            flex: 1;
            border: none;
            background: transparent;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.9rem;
            outline: none;
            color: #1e293b;
            padding: 10px 0;
        }

        .cf-input::placeholder {
            color: #94a3b8;
        }

        .cf-send {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--gold-main);
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            flex-shrink: 0;
        }

        .cf-send:hover {
            background: var(--gold-dark);
            transform: scale(1.05);
        }

        .empty-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            gap: 14px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.08);
            color: var(--gold-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        /* فیکس فونت ویندوز — همه‌جا Vazirmatn رو با این جایگزین کن */
        .chat-wrap,
        .cf-input,
        .msg-bubble,
        .conv-info,
        .ch-info {
            font-family: 'Vazir', Tahoma, sans-serif !important;
        }

        .chat-footer {
            position: relative;
        }

        .file-preview-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff7e6;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 8px 12px;
            margin-bottom: 8px;
            font-size: 0.82rem;
            color: #92400e;
        }

        .file-preview-bar button {
            margin-right: auto;
            background: none;
            border: none;
            color: #b45309;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .cf-template-btn {
            background: #f1f5f9;
            border: none;
            color: var(--navy);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
            flex-shrink: 0;
        }

        .cf-template-btn:hover {
            background: var(--gold-main);
            color: #fff;
        }

        .template-panel {
            position: absolute;
            bottom: 70px;
            left: 22px;
            right: 22px;
            max-width: 420px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            z-index: 50;
            overflow: hidden;
        }

        .template-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #f8fafc;
            font-weight: 800;
            font-size: 0.85rem;
            color: var(--navy);
            border-bottom: 1px solid #f1f5f9;
        }

        .template-panel-head button {
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
        }

        .template-list {
            max-height: 260px;
            overflow-y: auto;
            padding: 8px;
        }

        .template-item {
            display: block;
            width: 100%;
            text-align: right;
            background: none;
            border: none;
            padding: 10px 12px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.85rem;
            color: #334155;
            cursor: pointer;
            transition: 0.2s;
            white-space: pre-line;
        }

        .template-item:hover {
            background: #fdfbf7;
            color: var(--gold-dark);
        }

        @media(max-width:768px) {
            .template-panel {
                left: 12px;
                right: 12px;
                bottom: 80px;
            }
        }

        /* ─── ریسپانسیو (موبایل) ─── */
        @media(max-width:768px) {
            .chat-wrap {
                display: block;
                height: calc(100vh - 90px);
            }

            /* اگر مکالمه‌ای باز است: سایدبار مخفی، بخش چت تمام‌صفحه */
            .chat-wrap.has-active-chat .chat-sidebar {
                display: none;
            }

            .chat-wrap.has-active-chat .chat-main {
                display: flex;
                height: 100%;
            }

            /* اگر مکالمه‌ای باز نیست: بخش چت مخفی، سایدبار تمام‌صفحه */
            .chat-wrap:not(.has-active-chat) .chat-sidebar {
                display: flex;
                height: 100%;
                border-left: none;
            }

            .chat-wrap:not(.has-active-chat) .chat-main {
                display: none;
            }

            /* تنظیمات ظاهری المان‌ها در موبایل */
            .mobile-back {
                display: inline-flex;
            }

            .chat-head {
                padding: 12px;
            }

            .ch-actions {
                display: none;
                /* اکشن‌های اضافه را در هدر موبایل می‌توان مخفی کرد یا منوی دراپ‌داون گذاشت */
            }

            .msg-bubble {
                max-width: 85%;
            }

            .chat-footer {
                padding: 12px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- کلاس پویا برای تشخیص باز بودن یک چت خاص --}}
    <div class="chat-wrap {{ isset($activeConversation) ? 'has-active-chat' : '' }}">

        {{-- سایدبار مکالمات --}}
        <div class="chat-sidebar">
            <div class="cs-header">
                <h3>گفتگوها</h3>
                <div class="cs-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="جستجو...">
                </div>
            </div>

            <div class="conv-list">
                @forelse($conversations as $conv)
                    @php $unread = $conv->getUnreadCountFor('lawyer', auth('lawyer')->id()); @endphp
                    <a href="{{ route('lawyer.chat.show', $conv->id) }}"
                        class="conv-item {{ isset($activeConversation) && $activeConversation->id === $conv->id ? 'active' : '' }}">
                        <div class="conv-avatar">{{ mb_substr($conv->user->name ?? 'م', 0, 1) }}</div>
                        <div class="conv-info">
                            <h4>{{ $conv->user->name ?? 'موکل' }}</h4>
                            <p>
                                @if ($conv->latestMessage)
                                    {{ Str::limit($conv->latestMessage->message, 28) }}
                                @elseif($conv->consultation)
                                    مشاوره: {{ Str::limit($conv->consultation->title ?? '', 22) }}
                                @elseif($conv->case)
                                    پرونده: {{ Str::limit($conv->case->title ?? '', 22) }}
                                @else
                                    شروع گفتگو...
                                @endif
                            </p>
                        </div>
                        <div class="conv-meta">
                            <span class="conv-time">
                                @if ($conv->latestMessage)
                                    {{ $conv->latestMessage->created_at->format('H:i') }}
                                @endif
                            </span>
                            @if ($unread > 0)
                                <span class="unread-badge">{{ $unread }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div style="text-align:center;padding:40px 16px;color:#94a3b8;font-size:0.82rem;">
                        <i class="fas fa-comments" style="font-size:2rem;display:block;margin-bottom:10px;opacity:0.4;"></i>
                        هیچ مکالمه‌ای وجود ندارد
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ناحیه چت --}}
        {{-- ناحیه چت --}}
        <div class="chat-main">
            @if (isset($activeConversation))

                <header class="chat-head">
                    <div class="ch-left">
                        <a href="{{ route('lawyer.chat.index') }}" class="mobile-back" title="بازگشت به لیست">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <div class="ch-avatar">{{ mb_substr($activeConversation->user->name ?? 'م', 0, 1) }}</div>
                        <div class="ch-info">
                            <h3>{{ $activeConversation->user->name ?? 'موکل' }}</h3>
                            <span>
                                <span class="online-dot"></span>
                                {{ $activeConversation->user->phone ?? '' }}
                            </span>
                        </div>
                    </div>
                    <div class="ch-actions">
                        @if ($activeConversation->status === 'active')
                            <form method="POST" action="{{ route('lawyer.chat.close', $activeConversation->id) }}">
                                @csrf
                                <button type="submit" class="ch-btn close">
                                    <i class="fas fa-lock"></i> بستن مکالمه
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('lawyer.chat.reopen', $activeConversation->id) }}">
                                @csrf
                                <button type="submit" class="ch-btn reopen">
                                    <i class="fas fa-lock-open"></i> بازگشایی مکالمه
                                </button>
                            </form>
                        @endif
                    </div>
                </header>

                <div class="messages" id="msgContainer">
                    @forelse($messages as $msg)
                        @php $isLawyer = $msg->sender_type === 'lawyer'; @endphp
                        <div class="msg-row {{ $isLawyer ? 'from-lawyer' : 'from-client' }}">
                            <div class="msg-bubble">
                                {{ $msg->message }}

                                @if ($msg->attachments)
                                    @foreach ($msg->attachments as $att)
                                        <div class="attachment"
                                            style="display:flex;align-items:center;gap:8px;margin-top:6px;padding:6px 10px;background:rgba(0,0,0,0.05);border-radius:8px;">
                                            <i class="fas fa-file"></i>
                                            <a href="{{ asset('storage/' . $att['path']) }}" target="_blank"
                                                style="color:inherit;text-decoration:underline;font-size:0.82rem;">
                                                {{ $att['name'] ?? 'فایل پیوست' }}
                                            </a>
                                        </div>
                                    @endforeach
                                @endif

                                <span class="msg-meta">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if ($isLawyer && $msg->is_read)
                                        <i class="fas fa-check-double"></i>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;color:#94a3b8;margin:auto;">
                            <i class="fas fa-comments" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            هنوز پیامی رد و بدل نشده است
                        </div>
                    @endforelse
                </div>

                @if ($activeConversation->status === 'active')
                    <div class="chat-footer">
                        <form method="POST" action="{{ route('lawyer.chat.send', $activeConversation->id) }}"
                            enctype="multipart/form-data" id="lawyerChatForm">
                            @csrf

                            <div id="filePreviewBar" class="file-preview-bar" style="display:none;">
                                <i class="fas fa-paperclip"></i>
                                <span id="filePreviewName"></span>
                                <button type="button" id="fileRemoveBtn" title="حذف فایل"><i
                                        class="fas fa-times"></i></button>
                            </div>

                            <div id="templatePanel" class="template-panel" style="display:none;">
                                <div class="template-panel-head">
                                    <span><i class="fas fa-file-alt"></i> انتخاب قالب آماده</span>
                                    <button type="button" id="templateClose"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="template-list">
                                    @forelse(config('legal_templates', []) as $tpl)
                                        <button type="button" class="template-item" data-body="{{ $tpl['body'] }}">
                                            <strong>{{ $tpl['title'] }}</strong>
                                        </button>
                                    @empty
                                        <p style="padding:12px;color:#94a3b8;font-size:0.8rem;">قالبی تعریف نشده است.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="cf-form">
                                <label for="fileAttach" class="cf-attach" title="ارسال فایل">
                                    <i class="fas fa-paperclip"></i>
                                </label>
                                <input type="file" id="fileAttach" name="attachment" style="display:none;"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">

                                <button type="button" id="templateBtn" class="cf-template-btn" title="انتخاب قالب آماده">
                                    <i class="fas fa-file-alt"></i>
                                </button>

                                <input type="text" name="message" id="lawyerMsgInput" class="cf-input"
                                    placeholder="پیام خود را بنویسید..." autocomplete="off">
                                <button type="submit" class="cf-send">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div
                        style="padding:14px 22px;background:#fef3c7;text-align:center;font-size:0.85rem;color:#b45309;border-top:1px solid #fde68a;">
                        <i class="fas fa-lock" style="margin-left:5px;"></i> این مکالمه بسته شده است.
                    </div>
                @endif
            @else
                <div class="empty-chat">
                    <div class="empty-icon"><i class="fas fa-comments"></i></div>
                    <p style="font-weight:700;color:var(--navy);">یک گفتگو را از لیست انتخاب کنید</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const mc = document.getElementById('msgContainer');
                if (mc) mc.scrollTop = mc.scrollHeight;

                const fileInput = document.getElementById('fileAttach');
                const previewBar = document.getElementById('filePreviewBar');
                const previewName = document.getElementById('filePreviewName');
                const removeBtn = document.getElementById('fileRemoveBtn');

                fileInput?.addEventListener('change', function() {
                    if (this.files[0]) {
                        previewName.textContent = this.files[0].name;
                        previewBar.style.display = 'flex';
                    }
                });

                removeBtn?.addEventListener('click', () => {
                    fileInput.value = '';
                    previewBar.style.display = 'none';
                });

                const templateBtn = document.getElementById('templateBtn');
                const templatePanel = document.getElementById('templatePanel');
                const templateClose = document.getElementById('templateClose');
                const msgInput = document.getElementById('lawyerMsgInput');

                templateBtn?.addEventListener('click', () => {
                    templatePanel.style.display = templatePanel.style.display === 'none' ? 'block' : 'none';
                });
                templateClose?.addEventListener('click', () => templatePanel.style.display = 'none');

                document.querySelectorAll('.template-item').forEach(btn => {
                    btn.addEventListener('click', () => {
                        let body = btn.dataset.body;
                        @if (isset($activeConversation))
                            body = body.replaceAll('{client_name}', @json($activeConversation->user->name ?? ''));
                            @if ($activeConversation->case)
                                body = body.replaceAll('{case_number}', @json($activeConversation->case->case_number ?? ''));
                            @endif
                        @endif
                        msgInput.value = body;
                        templatePanel.style.display = 'none';
                        msgInput.focus();
                    });
                });

                document.addEventListener('click', (e) => {
                    if (templatePanel && !templatePanel.contains(e.target) && e.target !== templateBtn && !
                        templateBtn.contains(e.target)) {
                        templatePanel.style.display = 'none';
                    }
                });
            });
            document.getElementById('fileAttach')?.addEventListener('change', function() {
                const inp = document.querySelector('.cf-input');
                if (this.files[0] && inp) {
                    inp.placeholder = '📎 ' + this.files[0].name;
                    inp.style.color = 'var(--gold-dark)';
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                const fileInput = document.getElementById('fileAttach');
                const previewBar = document.getElementById('filePreviewBar');
                const previewName = document.getElementById('filePreviewName');
                const removeBtn = document.getElementById('fileRemoveBtn');

                fileInput?.addEventListener('change', function() {
                    if (this.files[0]) {
                        previewName.textContent = this.files[0].name;
                        previewBar.style.display = 'flex';
                    }
                });

                removeBtn?.addEventListener('click', () => {
                    fileInput.value = '';
                    previewBar.style.display = 'none';
                });

                const templateBtn = document.getElementById('templateBtn');
                const templatePanel = document.getElementById('templatePanel');
                const templateClose = document.getElementById('templateClose');
                const msgInput = document.getElementById('lawyerMsgInput');

                templateBtn?.addEventListener('click', () => {
                    templatePanel.style.display = templatePanel.style.display === 'none' ? 'block' : 'none';
                });
                templateClose?.addEventListener('click', () => templatePanel.style.display = 'none');

                document.querySelectorAll('.template-item').forEach(btn => {
                    btn.addEventListener('click', () => {
                        let body = btn.dataset.body;
                        @if (isset($activeConversation))
                            body = body.replaceAll('{client_name}', @json($activeConversation->user->name ?? ''));
                            @if ($activeConversation->case)
                                body = body.replaceAll('{case_number}', @json($activeConversation->case->case_number ?? ''));
                            @endif
                        @endif
                        msgInput.value = body;
                        templatePanel.style.display = 'none';
                        msgInput.focus();
                    });
                });

                // بستن پنل قالب با کلیک بیرون
                document.addEventListener('click', (e) => {
                    if (templatePanel && !templatePanel.contains(e.target) && e.target !== templateBtn && !
                        templateBtn.contains(e.target)) {
                        templatePanel.style.display = 'none';
                    }
                });
            });
        </script>
    @endpush

@endsection
