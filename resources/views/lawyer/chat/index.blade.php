@extends('layouts.lawyer')
@section('title', 'مرکز گفتگو')

@push('styles')
<style>
    .chat-wrap {
        display:grid; grid-template-columns:300px 1fr; height:calc(100vh - 130px);
        background:#fff; border-radius:16px; overflow:hidden;
        box-shadow:0 4px 20px rgba(0,0,0,0.06);
    }

    /* ─── سایدبار ─── */
    .chat-sidebar { border-left:1px solid #f1f5f9; display:flex; flex-direction:column; }
    .cs-header { padding:18px 16px; border-bottom:1px solid #f1f5f9; }
    .cs-header h3 { font-size:0.95rem; font-weight:800; color:var(--navy); margin:0 0 10px; }
    .cs-search { position:relative; }
    .cs-search input {
        width:100%; padding:8px 14px 8px 36px; border:1.5px solid #e2e8f0;
        border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.82rem; outline:none;
    }
    .cs-search i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; }

    .conv-list { flex:1; overflow-y:auto; padding:8px; }
    .conv-item {
        display:flex; align-items:center; gap:12px; padding:12px;
        border-radius:10px; cursor:pointer; transition:0.2s;
        text-decoration:none; color:inherit; margin-bottom:4px;
    }
    .conv-item:hover { background:#f8fafc; }
    .conv-item.active { background:rgba(212,175,55,0.08); border:1px solid rgba(212,175,55,0.2); }
    .conv-avatar {
        width:44px; height:44px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg,var(--navy),#1e3a5f);
        display:flex; align-items:center; justify-content:center;
        font-weight:800; font-size:1rem; color:var(--gold-main);
    }
    .conv-info { flex:1; min-width:0; }
    .conv-info h4 { font-size:0.85rem; font-weight:700; color:var(--navy); margin:0 0 3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .conv-info p { font-size:0.75rem; color:#94a3b8; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .conv-meta { display:flex; flex-direction:column; align-items:flex-end; gap:4px; }
    .conv-time { font-size:0.68rem; color:#94a3b8; white-space:nowrap; }
    .unread-badge { background:#ef4444; color:#fff; font-size:0.65rem; font-weight:800; padding:1px 6px; border-radius:10px; }

    /* ─── ناحیه چت ─── */
    .chat-main { display:flex; flex-direction:column; background:#fafafa; }
    .chat-head {
        padding:14px 22px; background:#fff; border-bottom:1px solid #f1f5f9;
        display:flex; align-items:center; justify-content:space-between;
    }
    .ch-left { display:flex; align-items:center; gap:14px; }
    .ch-avatar { width:46px; height:46px; border-radius:50%; background:var(--navy); color:var(--gold-main); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.1rem; }
    .ch-info h3 { font-size:0.95rem; font-weight:800; color:var(--navy); margin:0 0 2px; }
    .ch-info span { font-size:0.75rem; color:#94a3b8; }
    .online-dot { width:7px; height:7px; background:#10b981; border-radius:50%; display:inline-block; margin-left:4px; }
    .ch-actions { display:flex; gap:6px; }
    .ch-btn { padding:7px 14px; border-radius:8px; font-size:0.78rem; font-weight:700; cursor:pointer; border:none; font-family:'Vazirmatn',sans-serif; transition:0.2s; }
    .ch-btn.close { background:#fee2e2; color:#b91c1c; }
    .ch-btn.close:hover { background:#b91c1c; color:#fff; }
    .ch-btn.reopen { background:#d1fae5; color:#065f46; }
    .ch-btn.reopen:hover { background:#065f46; color:#fff; }

    .messages { flex:1; overflow-y:auto; padding:24px; display:flex; flex-direction:column; gap:16px; }
    .msg-row { display:flex; }
    .msg-row.from-client { justify-content:flex-start; }
    .msg-row.from-lawyer { justify-content:flex-end; }
    .msg-bubble { max-width:62%; padding:12px 16px; border-radius:16px; font-size:0.9rem; line-height:1.7; position:relative; }
    .msg-row.from-client .msg-bubble { background:#fff; color:#1e293b; border-radius:16px 16px 16px 4px; border-right:3px solid var(--gold-main); box-shadow:0 2px 8px rgba(0,0,0,0.04); }
    .msg-row.from-lawyer .msg-bubble { background:linear-gradient(135deg,var(--navy),#1e3a5f); color:#fff; border-radius:16px 16px 4px 16px; }
    .msg-meta { font-size:0.68rem; margin-top:6px; opacity:0.7; display:flex; align-items:center; gap:4px; justify-content:flex-end; }
    .msg-row.from-lawyer .msg-meta { color:rgba(255,255,255,0.7); }

    .chat-footer { padding:16px 22px; background:#fff; border-top:1px solid #f1f5f9; }
    .cf-form { display:flex; align-items:center; gap:10px; background:#f8fafc; border-radius:26px; padding:6px 6px 6px 16px; border:1px solid transparent; transition:0.3s; }
    .cf-form:focus-within { background:#fff; border-color:var(--gold-main); box-shadow:0 4px 12px rgba(212,175,55,0.1); }
    .cf-attach { color:#94a3b8; font-size:1.1rem; cursor:pointer; padding:8px; transition:0.2s; }
    .cf-attach:hover { color:var(--gold-main); }
    .cf-input { flex:1; border:none; background:transparent; font-family:'Vazirmatn',sans-serif; font-size:0.9rem; outline:none; color:#1e293b; padding:10px 0; }
    .cf-input::placeholder { color:#94a3b8; }
    .cf-send { width:42px; height:42px; border-radius:50%; background:var(--gold-main); color:#fff; border:none; display:flex; align-items:center; justify-content:center; font-size:1rem; cursor:pointer; transition:0.3s; flex-shrink:0; }
    .cf-send:hover { background:var(--gold-dark); transform:scale(1.05); }

    .empty-chat { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#94a3b8; gap:14px; }
    .empty-icon { width:80px; height:80px; border-radius:50%; background:rgba(212,175,55,0.08); color:var(--gold-main); display:flex; align-items:center; justify-content:center; font-size:2.5rem; }

    @media(max-width:768px) { .chat-wrap { grid-template-columns:80px 1fr; } .conv-info, .conv-meta, .cs-header h3, .cs-search { display:none; } }
</style>
@endpush

@section('content')

<div class="chat-wrap">
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
                            @if($conv->latestMessage)
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
                            @if($conv->latestMessage)
                                {{ $conv->latestMessage->created_at->format('H:i') }}
                            @endif
                        </span>
                        @if($unread > 0)
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
    <div class="chat-main">
        @if(isset($activeConversation))
            <div class="chat-head">
                <div class="ch-left">
                    <div class="ch-avatar">{{ mb_substr($activeConversation->user->name ?? 'م', 0, 1) }}</div>
                    <div class="ch-info">
                        <h3>{{ $activeConversation->user->name ?? 'موکل' }}</h3>
                        <span>
                            <span class="online-dot"></span>
                            @if($activeConversation->consultation)
                                مشاوره: {{ Str::limit($activeConversation->consultation->title ?? '', 25) }}
                            @elseif($activeConversation->case)
                                پرونده: {{ $activeConversation->case->case_number ?? '' }}
                            @else
                                گفتگوی آزاد
                            @endif
                            &nbsp;·&nbsp;
                            {{ $activeConversation->status === 'active' ? 'فعال' : 'بسته' }}
                        </span>
                    </div>
                </div>
                <div class="ch-actions">
                    @if($activeConversation->user)
                        <a href="{{ route('lawyer.clients.show', $activeConversation->user) }}"
                           style="padding:7px 14px;background:#f1f5f9;color:var(--navy);border-radius:8px;font-size:0.78rem;font-weight:700;text-decoration:none;">
                            <i class="fas fa-user"></i> پروفایل
                        </a>
                    @endif
                    @if($activeConversation->status === 'active')
                        <form method="POST" action="{{ route('lawyer.chat.close', $activeConversation->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="ch-btn close">
                                <i class="fas fa-times"></i> بستن
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('lawyer.chat.reopen', $activeConversation->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="ch-btn reopen">
                                <i class="fas fa-redo"></i> باز کردن
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="messages" id="msgContainer">
                @forelse($messages as $msg)
                    @php $isLawyer = $msg->sender_type === 'lawyer'; @endphp
                    <div class="msg-row {{ $isLawyer ? 'from-lawyer' : 'from-client' }}">
                        <div class="msg-bubble">
                            {{ $msg->message }}
                            @if($msg->attachments)
                                @foreach($msg->attachments as $att)
                                    <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);padding:8px 12px;border-radius:8px;margin-top:8px;">
                                        <i class="fas fa-file" style="opacity:0.7;"></i>
                                        <span style="font-size:0.78rem;">{{ $att['name'] ?? 'فایل' }}</span>
                                    </div>
                                @endforeach
                            @endif
                            <div class="msg-meta">
                                {{ $msg->created_at->format('H:i') }}
                                @if($isLawyer)
                                    <i class="fas {{ $msg->is_read ? 'fa-check-double' : 'fa-check' }}" style="font-size:0.65rem;"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:40px;color:#94a3b8;font-size:0.85rem;">
                        <i class="fas fa-hand-sparkles" style="font-size:2rem;display:block;margin-bottom:10px;color:var(--gold-main);opacity:0.5;"></i>
                        شروع گفتگو کنید...
                    </div>
                @endforelse
            </div>

            @if($activeConversation->status === 'active')
                <div class="chat-footer">
                    <form method="POST" action="{{ route('lawyer.chat.send', $activeConversation->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="cf-form">
                            <label for="fileAttach" class="cf-attach" title="ارسال فایل">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <input type="file" id="fileAttach" name="attachment" style="display:none;" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <input type="text" name="message" class="cf-input" placeholder="پیام خود را بنویسید..." autocomplete="off">
                            <button type="submit" class="cf-send">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div style="padding:14px 22px;background:#fef3c7;text-align:center;font-size:0.85rem;color:#b45309;border-top:1px solid #fde68a;">
                    <i class="fas fa-lock" style="margin-left:5px;"></i> این مکالمه بسته شده است.
                </div>
            @endif
        @else
            <div class="empty-chat">
                <div class="empty-icon"><i class="fas fa-comments"></i></div>
                <h3 style="color:var(--navy);font-weight:800;">مرکز گفتگو</h3>
                <p>یک مکالمه را از لیست انتخاب کنید</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mc = document.getElementById('msgContainer');
    if (mc) mc.scrollTop = mc.scrollHeight;
});
document.getElementById('fileAttach')?.addEventListener('change', function() {
    const inp = document.querySelector('.cf-input');
    if (this.files[0] && inp) {
        inp.placeholder = '📎 ' + this.files[0].name;
        inp.style.color = 'var(--gold-dark)';
    }
});
</script>
@endpush

@endsection