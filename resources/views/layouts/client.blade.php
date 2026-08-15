<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'داشبورد') | دفتر وکالت ابدالی و جوشقانی</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#102a43">
    <meta name="vapid-key" content="{{ config('webpush.vapid.public_key') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <script>window.__shouldSubscribePush = true;</script>
    @endauth
    <!-- iOS specific -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ابدالی و جوشقانی">
    <link rel="apple-touch-icon" href="/assets/icons/icon-192.png">

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-body: #fdfbf7;
            --bg-white: #ffffff;
            --gold-main: #c5a059;
            --gold-dark: #9e7f41;
            --navy: #102a43;
            --text-body: #595048;
            --radius-md: 15px;
            --radius-sm: 8px;
            --transition: all 0.3s ease;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-body);
            line-height: 1.8;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827z' fill='%23c5a059' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1;
            pointer-events: none;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }

        /* ─── Header ─────────────────────────────────────────── */
        .header {
            background: rgba(253, 251, 247, 0.98);
            border-bottom: 3px solid var(--gold-main);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }

        .nav-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand i {
            font-size: 2.2rem;
            color: var(--gold-main);
        }

        .brand-text h1 {
            font-size: 1.2rem;
            font-weight: 900;
            color: var(--navy);
            margin: 0;
        }

        .brand-text span {
            font-size: 0.75rem;
            color: var(--gold-dark);
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-body);
            font-size: 0.95rem;
            position: relative;
            padding-bottom: 3px;
        }

        .nav-link.active,
        .nav-link:hover {
            color: var(--navy);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: 0;
            width: 100%;
            height: 3px;
            background: var(--gold-main);
            border-radius: 5px 5px 0 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notif-btn {
            position: relative;
            color: var(--navy);
            font-size: 1.2rem;
            cursor: pointer;
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            left: -5px;
            background: #e74c3c;
            color: #fff;
            font-size: 0.6rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vip-badge {
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .profile-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--navy);
            color: var(--gold-main);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--gold-main);
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
        }

        .dropdown-wrapper {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: calc(100% + 10px);
            background: #fff;
            border: 1px solid #eee;
            border-radius: var(--radius-sm);
            min-width: 180px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            z-index: 200;
            overflow: hidden;
        }

        .dropdown-wrapper:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .dropdown-item:hover {
            background: #fdfbf7;
            color: var(--gold-dark);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            font-size: 0.9rem;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
            text-align: right;
            font-family: inherit;
            color: #e74c3c;
            transition: 0.2s;
        }

        .logout-btn:hover {
            background: #fef2f2;
        }

        .hamburger-client {
            display: none;
            font-size: 1.5rem;
            color: var(--navy);
            cursor: pointer;
            margin-right: 10px;
        }

        .page-wrapper {
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .flash-container {
            margin-bottom: 20px;
        }

        .flash {
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .flash-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .flash-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* 🔴 موبایل: نمایش منوی همبرگری و باز شدن زیر هدر 🔴 */
        @media (max-width: 992px) {
            .hamburger-client {
                display: block;
            }

            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                padding: 10px 20px 20px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
                border-top: 1px solid #f1f5f9;
                gap: 0;
            }

            .nav-menu.open {
                display: flex;
            }

            .nav-link {
                display: block;
                padding: 12px 0;
                border-bottom: 1px solid #f8fafc;
            }

            .nav-link.active::after {
                display: none;
            }

            .nav-link.active {
                color: var(--gold-main);
                font-weight: 800;
            }

            .brand-text h1 {
                font-size: 1rem;
            }

            .vip-badge span {
                display: none;
            }

            /* مخفی کردن متن "موکل ویژه" در موبایل برای جلوگیری از شلوغی */
        }
    </style>
    @stack('styles')
</head>

<body>

    <header class="header">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="brand">
                <i class="fas fa-scale-balanced"></i>
                <div class="brand-text">
                    <h1>ابدالی و جوشقانی</h1>
                    <span>دفتر وکالت تخصصی</span>
                </div>
            </a>

            <ul class="nav-menu" id="clientNavMenu">
                <li>
                    <a href="{{ route('home') }}" class="nav-link" style="color: var(--gold-main);">
                        <i class="fas fa-home"></i> سایت اصلی
                    </a>
                </li>
                <li><a href="{{ route('dashboard.index') }}"
                        class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">داشبورد</a></li>
                @if (auth()->user()->isSpecial())
                    <li><a href="{{ route('client.cases.index') }}"
                            class="nav-link {{ request()->routeIs('client.cases*') ? 'active' : '' }}">پرونده‌ام</a>
                    </li>
                    <li><a href="{{ route('client.installments.index') }}"
                            class="nav-link {{ request()->routeIs('client.installments*') ? 'active' : '' }}">اقساط</a>
                    </li>
                @else
                    <li><a href="{{ route('client.consultations.index') }}"
                            class="nav-link {{ request()->routeIs('client.consultations*') ? 'active' : '' }}">مشاوره‌ها</a>
                    </li>
                @endif
                <li><a href="{{ route('client.chat.index') }}"
                        class="nav-link {{ request()->routeIs('client.chat*') ? 'active' : '' }}">پیام‌ها</a></li>
                <li><a href="{{ route('reserve.index') }}"
                        class="nav-link {{ request()->routeIs('reserve*') ? 'active' : '' }}">رزرو نوبت</a></li>
            </ul>

            <div class="header-right">
                <div class="user-menu">
                    @if (auth()->user()->isSpecial())
                        <span class="vip-badge"><i class="fas fa-crown"></i> <span>موکل ویژه</span></span>
                    @endif

                    <span class="notif-btn">
                        <i class="far fa-bell"></i>
                        <span class="notif-badge">۲</span>
                    </span>

                    <div class="dropdown-wrapper">
                        <div class="profile-btn">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="dropdown-menu">
                            <div style="padding:12px 16px;border-bottom:1px solid #eee;">
                                <div style="font-weight:700;color:var(--navy);font-size:0.9rem;">
                                    {{ auth()->user()->name }}
                                </div>
                                <div style="font-size:0.78rem;color:#999;">{{ auth()->user()->phone }}</div>
                            </div>
                            <a href="{{ route('client.profile') }}" class="dropdown-item"><i class="far fa-user"></i>
                                پروفایل</a>
                            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                                @csrf
                                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>
                                    خروج</button>
                            </form>
                        </div>
                    </div>
                </div>

                <span class="hamburger-client" onclick="toggleClientMenu()">
                    <i class="fas fa-bars"></i>
                </span>
            </div>
        </div>
    </header>

    <div class="page-wrapper">
        @if (session()->hasAny(['success', 'error', 'warning']))
            <div class="flash-container">
                @if (session('success'))
                    <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        function toggleClientMenu() {
            document.getElementById('clientNavMenu').classList.toggle('open');
        }
    </script>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(console.error);
            });
        }
    </script>
</body>

</html>