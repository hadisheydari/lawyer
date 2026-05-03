<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل وکیل | @yield('title', 'داشبورد')</title>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --gold-main: #d4af37;
            --gold-dark: #aa8222;
            --gold-light: #f9f1d8;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --navy-dark: #020617;
            --bg-body: #f1f5f9;
            --sidebar-width: 270px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px -5px rgba(0,0,0,0.1);
        }

        *,*::before,*::after { box-sizing: border-box; margin:0; padding:0; }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background: var(--bg-body);
            color: #334155;
            display: flex;
            min-height: 100vh;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--navy-dark);
            color: #fff;
            position: fixed;
            top: 0; bottom: 0; right: 0;
            z-index: 1001;
            display: flex;
            flex-direction: column;
            box-shadow: -5px 0 30px rgba(0,0,0,0.25);
            overflow: hidden;
        }

        .sidebar-header {
            padding: 24px 20px;
            background: rgba(212,175,55,0.08);
            border-bottom: 1px solid rgba(212,175,55,0.15);
            display: flex; align-items: center; gap: 12px;
            flex-shrink: 0;
        }
        .sidebar-header i { font-size: 1.8rem; color: var(--gold-main); }
        .sidebar-header h1 { font-size: 1rem; font-weight: 900; color: #fff; margin: 0; line-height: 1.3; }
        .sidebar-header small { color: var(--gold-main); font-size: 0.68rem; display: block; }

        .sidebar-menu {
            flex: 1; padding: 14px 12px; overflow-y: auto;
            list-style: none; margin: 0;
        }
        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        .menu-label {
            font-size: 0.68rem; text-transform: uppercase; letter-spacing: 1.5px;
            color: #475569; padding: 18px 10px 8px; font-weight: 800;
        }

        .menu-item { margin-bottom: 3px; }
        .menu-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; color: #94a3b8;
            text-decoration: none; border-radius: 10px;
            font-weight: 600; font-size: 0.88rem;
            transition: var(--transition); position: relative;
        }
        .menu-link i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }
        .menu-link:hover { background: rgba(255,255,255,0.06); color: #e2e8f0; }
        .menu-link.active {
            background: linear-gradient(135deg, rgba(212,175,55,0.2), rgba(170,130,34,0.15));
            color: var(--gold-main);
            border-right: 3px solid var(--gold-main);
        }
        .menu-link.active i { color: var(--gold-main); }

        .badge-new {
            margin-right: auto; background: #ef4444; color: #fff;
            font-size: 0.65rem; padding: 1px 7px; border-radius: 10px;
            font-weight: 800; animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.7} }

        /* ─── Main Content ─── */
        .main-content {
            flex: 1; margin-right: var(--sidebar-width);
            display: flex; flex-direction: column; min-height: 100vh;
        }

        /* ─── Top Header ─── */
        .top-header {
            height: 68px; background: #fff;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 30px;
            position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .header-title { font-weight: 800; color: var(--navy); font-size: 1.05rem; }
        .header-breadcrumb { font-size: 0.78rem; color: #94a3b8; margin-top: 2px; }

        .header-right { display: flex; align-items: center; gap: 18px; }

        .notif-btn { position: relative; font-size: 1.25rem; color: #64748b; cursor: pointer; transition: 0.2s; }
        .notif-btn:hover { color: var(--navy); }
        .notif-dot { position: absolute; top: 1px; left: 0; width: 9px; height: 9px; background: #ef4444; border: 2px solid #fff; border-radius: 50%; }

        .avatar-btn {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; padding: 6px 10px; border-radius: 40px;
            transition: 0.2s; text-decoration: none;
        }
        .avatar-btn:hover { background: #f1f5f9; }
        .avatar-circle {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--navy); color: var(--gold-main);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 0.95rem;
            border: 2px solid rgba(212,175,55,0.3);
        }
        .avatar-info .name { display: block; font-weight: 700; font-size: 0.85rem; color: var(--navy); }
        .avatar-info .role { display: block; font-size: 0.7rem; color: #94a3b8; }

        /* ─── Content Body ─── */
        .content-body { padding: 28px 30px; flex: 1; }

        /* ─── Flash ─── */
        .flash-wrap { position: fixed; bottom: 24px; left: 24px; z-index: 9999; width: 340px; }
        .flash-alert {
            padding: 14px 18px; border-radius: 12px; margin-bottom: 10px;
            display: flex; align-items: center; gap: 12px; font-weight: 600; font-size: 0.88rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            animation: slideUp 0.4s ease;
        }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .flash-success { background:#ecfdf5; color:#065f46; border-right:4px solid #10b981; }
        .flash-error   { background:#fef2f2; color:#991b1b; border-right:4px solid #ef4444; }

        @media(max-width:1024px) {
            .sidebar { transform:translateX(100%); }
            .sidebar.open { transform:translateX(0); }
            .main-content { margin-right:0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-scale-balanced"></i>
        <div>
            <h1>ابدالی و جوشقانی</h1>
            <small>پنل مدیریت وکیل</small>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-label">اصلی</li>
        <li class="menu-item">
            <a href="{{ route('lawyer.dashboard') }}"
               class="menu-link {{ request()->routeIs('lawyer.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> داشبورد
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('lawyer.settings.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.settings*') && request()->routeIs('*calendar') ? 'active' : '' }}"
               style="display:none" id="calLink">
            </a>
        </li>

        <li class="menu-label">مدیریت مراجعین</li>
        <li class="menu-item">
            <a href="{{ route('lawyer.consultations.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.consultations*') ? 'active' : '' }}">
                <i class="fas fa-headset"></i> نوبت‌های مشاوره
                @php $pendingCount = auth('lawyer')->user()?->consultations()->where('status','pending')->count() ?? 0; @endphp
                @if($pendingCount > 0)
                    <span class="badge-new">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('lawyer.cases.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.cases*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> پرونده‌های حقوقی
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('lawyer.clients.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.clients*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> موکلین
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('lawyer.chat.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.chat*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> مرکز گفتگو
                @php $unreadMsgs = auth('lawyer')->user()?->conversations()->get()->sum(fn($c) => $c->getUnreadCountFor('lawyer', auth('lawyer')->id())) ?? 0; @endphp
                @if($unreadMsgs > 0)
                    <span class="badge-new">{{ $unreadMsgs }}</span>
                @endif
            </a>
        </li>

        <li class="menu-label">ابزارها و محتوا</li>
        <li class="menu-item">
            <a href="{{ route('lawyer.settings.index') }}#calendar"
               class="menu-link {{ request()->routeIs('lawyer.calendar') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> تقویم و برنامه کاری
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('lawyer.payments.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.payments*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i> پرداخت‌ها
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('lawyer.articles.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.articles*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> مقالات
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('lawyer.comments.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.comments*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> نظرات
                @php $pendingComments = 0; try { $pendingComments = \App\Models\ArticleComment::whereHas('article', fn($q) => $q->where('lawyer_id', auth('lawyer')->id()))->where('status','pending')->count(); } catch(\Exception $e) {} @endphp
                @if($pendingComments > 0)
                    <span class="badge-new">{{ $pendingComments }}</span>
                @endif
            </a>
        </li>

        <li class="menu-label">تنظیمات</li>
        <li class="menu-item">
            <a href="{{ route('lawyer.settings.index') }}"
               class="menu-link {{ request()->routeIs('lawyer.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> تنظیمات و پروفایل
            </a>
        </li>
    </ul>

    <div style="padding:16px;border-top:1px solid rgba(255,255,255,0.06);flex-shrink:0;">
        <form action="{{ route('lawyer.logout') }}" method="POST">
            @csrf
            <button type="submit"
                    style="width:100%;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;padding:10px;border-radius:10px;font-family:inherit;font-weight:700;font-size:0.85rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:0.3s;"
                    onmouseover="this.style.background='rgba(239,68,68,0.15)'"
                    onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                <i class="fas fa-sign-out-alt"></i> خروج از حساب
            </button>
        </form>
    </div>
</aside>

<main class="main-content">
    <header class="top-header">
        <div>
            <div class="header-title">@yield('title', 'داشبورد')</div>
        </div>
        <div class="header-right">
            <div class="notif-btn">
                <i class="far fa-bell"></i>
                <span class="notif-dot"></span>
            </div>
            <a href="{{ route('lawyer.settings.index') }}" class="avatar-btn">
                <div class="avatar-circle">
                    {{ mb_substr(auth('lawyer')->user()->name ?? 'و', 0, 1) }}
                </div>
                <div class="avatar-info">
                    <span class="name">{{ auth('lawyer')->user()->name ?? '' }}</span>
                    <span class="role">وکیل پایه یک</span>
                </div>
            </a>
        </div>
    </header>

    <div class="content-body">
        @yield('content')
    </div>
</main>

<div class="flash-wrap">
    @if(session('success'))
        <div class="flash-alert flash-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash-alert flash-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
</div>

@stack('scripts')
<script>
    // Auto-dismiss flash messages
    setTimeout(() => {
        document.querySelectorAll('.flash-alert').forEach(el => {
            el.style.transition = '0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
</body>
</html>