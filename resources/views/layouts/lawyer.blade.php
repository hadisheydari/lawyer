<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل وکیل') | ابدالی و جوشقانی</title>

    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-body: #f3f4f6;
            --bg-white: #ffffff;
            --gold-main: #c5a059;
            --gold-light: #e6cfa3;
            --gold-dark: #9e7f41;
            --navy: #102a43;
            --navy-dark: #0a1c2e;
            --text-heading: #1f2937;
            --text-body: #4b5563;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --shadow-soft: 0 4px 6px -1px rgba(0,0,0,0.05);
            --shadow-card: 0 10px 15px -3px rgba(0,0,0,0.05);
            --radius: 12px;
            --transition: all 0.3s ease;
            --sidebar-width: 280px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-body);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        a { text-decoration: none; color: inherit; transition: var(--transition); }

        /* ─── Sidebar ────────────────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--navy);
            color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-left: 1px solid rgba(255,255,255,0.05);
            transition: var(--transition);
            z-index: 100;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-weight: 800;
            font-size: 1rem;
            color: var(--gold-main);
            gap: 10px;
            padding: 0 20px;
        }
        .sidebar-brand i { font-size: 1.4rem; }

        .sidebar-menu {
            flex: 1;
            padding: 20px 12px;
            overflow-y: auto;
        }

        .menu-label {
            font-size: 0.7rem;
            color: #6b7280;
            margin: 18px 10px 8px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 11px 14px;
            margin-bottom: 3px;
            border-radius: 8px;
            color: #d1d5db;
            font-weight: 500;
            font-size: 0.9rem;
            gap: 12px;
            transition: var(--transition);
            cursor: pointer;
        }

        .menu-item i { width: 18px; text-align: center; font-size: 0.95rem; }

        .menu-item:hover {
            background-color: rgba(255,255,255,0.08);
            color: var(--gold-light);
        }

        .menu-item.active {
            background-color: rgba(197, 160, 89, 0.15);
            color: var(--gold-main);
            border-right: 3px solid var(--gold-main);
        }

        .menu-badge {
            margin-right: auto;
            background: var(--danger);
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 700;
        }

        .sidebar-profile {
            padding: 15px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lawyer-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--gold-main);
            background: var(--navy-dark);
            display: flex; align-items: center; justify-content: center;
            color: var(--gold-main); font-weight: bold; font-size: 1rem;
            flex-shrink: 0;
        }

        .lawyer-info h4 { margin: 0; font-size: 0.88rem; color: #fff; font-weight: 700; }
        .lawyer-info span { font-size: 0.72rem; color: #9ca3af; }

        /* ─── Main Content ───────────────────────────────────────────────── */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Topbar */
        .topbar {
            height: 70px;
            background-color: var(--bg-white);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: var(--shadow-soft);
            z-index: 10;
            flex-shrink: 0;
        }

        .topbar-right { display: flex; align-items: center; gap: 15px; }
        .topbar-left { display: flex; align-items: center; gap: 20px; }

        .toggle-menu {
            display: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--text-body);
        }

        .page-breadcrumb { font-size: 0.85rem; color: #9ca3af; }
        .page-breadcrumb span { color: var(--navy); font-weight: 600; }

        .search-wrapper {
            position: relative;
            width: 280px;
        }
        .search-wrapper input {
            width: 100%;
            padding: 9px 40px 9px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
            font-family: inherit;
            font-size: 0.88rem;
            transition: 0.3s;
        }
        .search-wrapper input:focus {
            outline: none;
            border-color: var(--gold-main);
            background: #fff;
        }
        .search-wrapper i { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; }

        .topbar-icon-btn {
            position: relative;
            font-size: 1.15rem;
            color: #6b7280;
            cursor: pointer;
            transition: 0.2s;
        }
        .topbar-icon-btn:hover { color: var(--navy); }

        .topbar-badge {
            position: absolute; top: -5px; right: -5px;
            background: var(--danger); color: #fff;
            font-size: 0.6rem; width: 16px; height: 16px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* ─── Content Scroll Area ────────────────────────────────────────── */
        .content-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
        }

        /* ─── Page Header ────────────────────────────────────────────────── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title h2 { margin: 0; font-size: 1.5rem; color: var(--navy); font-weight: 800; }
        .page-title p { margin: 5px 0 0; color: #6b7280; font-size: 0.88rem; }

        /* ─── Flash Messages ─────────────────────────────────────────────── */
        .flash { padding: 14px 18px; border-radius: var(--radius); font-size: 0.88rem; font-weight: 500; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .flash-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .flash-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        /* ─── Cards ──────────────────────────────────────────────────────── */
        .card {
            background: var(--bg-white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            padding: 25px;
        }

        .card-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
        }
        .card-title { font-size: 1.05rem; font-weight: 700; color: var(--navy); }
        .card-action { color: var(--gold-dark); font-size: 0.85rem; }
        .card-action:hover { color: var(--gold-main); }

        /* ─── Responsive ─────────────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .search-wrapper { width: 200px; }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                right: calc(-1 * var(--sidebar-width));
                height: 100%;
                top: 0;
            }
            .sidebar.open { right: 0; box-shadow: 5px 0 20px rgba(0,0,0,0.3); }
            .toggle-menu { display: block; }
            .search-wrapper { display: none; }
            .content-scroll { padding: 20px 15px; }
        }

        /* overlay برای موبایل */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
        }
        .sidebar-overlay.show { display: block; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-balance-scale"></i>
        <span>پنل وکالت</span>
    </div>

    <nav class="sidebar-menu">
        <span class="menu-label">اصلی</span>

        <a href="{{ route('lawyer.dashboard') }}"
           class="menu-item {{ request()->routeIs('lawyer.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> داشبورد
        </a>

        <a href="{{ route('lawyer.cases.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.cases*') ? 'active' : '' }}">
            <i class="fas fa-folder-open"></i> پرونده‌ها
            @php $openCases = 0; @endphp
            {{-- @if($openCases) <span class="menu-badge">{{ $openCases }}</span> @endif --}}
        </a>

        <a href="{{ route('lawyer.clients.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.clients*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> موکلین
        </a>

        <a href="{{ route('lawyer.consultations.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.consultations*') ? 'active' : '' }}">
            <i class="fas fa-comments"></i> مشاوره‌ها
        </a>

        <a href="{{ route('lawyer.calendar') }}"
           class="menu-item {{ request()->routeIs('lawyer.calendar') ? 'active' : '' }}">
            <i class="far fa-calendar-alt"></i> تقویم
        </a>

        <a href="{{ route('lawyer.chat.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.chat*') ? 'active' : '' }}">
            <i class="far fa-comment-dots"></i> پیام‌ها
            <span class="menu-badge">۳</span>
        </a>

        <span class="menu-label">محتوا</span>

        <a href="{{ route('lawyer.articles.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.articles*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> مقالات
        </a>

        <a href="{{ route('lawyer.comments.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.comments*') ? 'active' : '' }}">
            <i class="far fa-comments"></i> نظرات
        </a>

        <span class="menu-label">مالی</span>

        <a href="{{ route('lawyer.payments.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.payments*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> پرداخت‌ها
        </a>

        <span class="menu-label">تنظیمات</span>

        <a href="{{ route('lawyer.settings.index') }}"
           class="menu-item {{ request()->routeIs('lawyer.settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> تنظیمات
        </a>

        <a href="{{ route('lawyer.schedule') }}"
           class="menu-item {{ request()->routeIs('lawyer.schedule') ? 'active' : '' }}">
            <i class="far fa-clock"></i> ساعات کاری
        </a>
    </nav>

    {{-- Lawyer Profile --}}
    <div class="sidebar-profile">
        <div class="lawyer-avatar">
            @if(auth()->guard('lawyer')->user()->image)
                <img src="{{ asset('storage/' . auth()->guard('lawyer')->user()->image) }}" alt="">
            @else
                {{ mb_substr(auth()->guard('lawyer')->user()->name, 0, 1) }}
            @endif
        </div>
        <div class="lawyer-info">
            <h4>{{ auth()->guard('lawyer')->user()->name }}</h4>
            <span>وکیل پایه {{ auth()->guard('lawyer')->user()->license_grade }}</span>
        </div>
    </div>
</aside>

{{-- Main Content --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-right">
            <span class="toggle-menu" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </span>

            <div class="page-breadcrumb">
                پنل وکیل / <span>@yield('title', 'داشبورد')</span>
            </div>
        </div>

        <div class="topbar-left">
            <div class="search-wrapper">
                <input type="text" placeholder="جستجو...">
                <i class="fas fa-search"></i>
            </div>

            <a href="{{ route('lawyer.chat.index') }}" class="topbar-icon-btn">
                <i class="far fa-comment-dots"></i>
                <span class="topbar-badge">۳</span>
            </a>

            <span class="topbar-icon-btn">
                <i class="far fa-bell"></i>
                <span class="topbar-badge">۵</span>
            </span>

            <a href="{{ route('home') }}" class="topbar-icon-btn" title="مشاهده سایت">
                <i class="fas fa-external-link-alt"></i>
            </a>

            <form method="POST" action="{{ route('lawyer.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="topbar-icon-btn" style="border: none; background: none; cursor: pointer; color: #ef4444;" title="خروج">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Scrollable Content --}}
    <div class="content-scroll">
        @if(session()->hasAny(['success', 'error', 'warning']))
            @if(session('success'))
                <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="flash flash-warning"><i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}</div>
            @endif
        @endif

        @yield('content')
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
</script>

@stack('scripts')
</body>
</html>
