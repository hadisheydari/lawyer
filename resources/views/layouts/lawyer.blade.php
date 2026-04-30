<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل وکیل | @yield('title', 'داشبورد')</title>

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --gold-main: #d4af37;
            --gold-dark: #aa8222;
            --gold-light: #f9f1d8;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --navy-dark: #020617;
            --bg-body: #f8fafc;
            --sidebar-width: 280px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px -5px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-body);
            color: #334155;
            margin: 0;
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
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: -5px 0 25px rgba(0,0,0,0.2);
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-header i { font-size: 2rem; color: var(--gold-main); }
        .sidebar-header h1 { font-size: 1.1rem; font-weight: 900; margin: 0; color: #fff; }

        .sidebar-menu {
            flex: 1;
            padding: 20px 15px;
            overflow-y: auto;
            list-style: none;
            margin: 0;
        }

        .menu-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #64748b;
            padding: 15px 15px 10px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .menu-item { margin-bottom: 5px; }
        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition);
        }
        .menu-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .menu-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .menu-link.active {
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            box-shadow: 0 8px 15px rgba(212, 175, 55, 0.2);
        }
        .menu-link.active i { color: #fff; }

        .unread-badge {
            margin-right: auto;
            background: #ef4444;
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* ─── Main Content ─── */
        .main-content {
            flex: 1;
            margin-right: var(--sidebar-width);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        /* ─── Header ─── */
        .top-header {
            height: 75px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }

        .header-left { display: flex; align-items: center; gap: 20px; }
        .header-right { display: flex; align-items: center; gap: 25px; }

        .notif-btn {
            position: relative;
            font-size: 1.3rem;
            color: #64748b;
            cursor: pointer;
            transition: 0.2s;
        }
        .notif-btn:hover { color: var(--navy); }
        .notif-dot {
            position: absolute; top: 2px; left: 0;
            width: 10px; height: 10px; background: #ef4444;
            border: 2px solid #fff; border-radius: 50%;
        }

        .user-profile-dropdown {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 5px;
            border-radius: 50px;
            transition: 0.2s;
        }
        .user-profile-dropdown:hover { background: #f1f5f9; }
        .user-info { text-align: left; } /* در RTL برعکس می‌شود */
        .user-info .name { display: block; font-weight: 800; font-size: 0.9rem; color: var(--navy); }
        .user-info .role { display: block; font-size: 0.75rem; color: #94a3b8; }
        .user-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            background: var(--navy); color: var(--gold-main);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; border: 2px solid var(--gold-light);
        }

        .content-body { padding: 35px; flex: 1; }

        /* ─── Flash Messages ─── */
        .flash-wrapper { position: fixed; top: 90px; left: 35px; z-index: 1050; width: 350px; }
        .alert {
            padding: 16px 20px; border-radius: 12px; margin-bottom: 10px;
            display: flex; align-items: center; gap: 12px; font-weight: 600;
            box-shadow: var(--shadow-md); animation: slideIn 0.4s ease;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        .alert-success { background: #ecfdf5; color: #065f46; border-right: 5px solid #10b981; }
        .alert-error { background: #fef2f2; color: #991b1b; border-right: 5px solid #ef4444; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-right: 0; }
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
                <small style="color: var(--gold-main); font-size: 0.7rem;">پنل مدیریت وکیل</small>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-label">اصلی</li>
            <li class="menu-item">
                <a href="{{ route('lawyer.dashboard') }}" class="menu-link {{ request()->routeIs('lawyer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> داشبورد
                </a>
            </li>

            <li class="menu-label">مدیریت مراجعین</li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-headset"></i> نوبت‌های مشاوره
                    <span class="unread-badge">۳ جدید</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-briefcase"></i> پرونده‌های حقوقی
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-comments"></i> مرکز گفتگو
                </a>
            </li>

            <li class="menu-label">ابزارها و محتوا</li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-calendar-alt"></i> برنامه‌ریزی زمانی
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-newspaper"></i> مقالات من
                </a>
            </li>

            <li class="menu-label">تنظیمات</li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-user-circle"></i> ویرایش پروفایل
                </a>
            </li>
        </ul>

        <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="width:100%; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 12px; border-radius: 12px; font-family: inherit; font-weight: 700; cursor: pointer; transition: 0.3s;">
                    <i class="fas fa-sign-out-alt"></i> خروج از حساب
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="header-left">
                <div style="font-weight: 800; color: var(--navy); font-size: 1.1rem;">
                    @yield('title')
                </div>
            </div>

            <div class="header-right">
                <div class="notif-btn">
                    <i class="far fa-bell"></i>
                    <span class="notif-dot"></span>
                </div>

                <div class="user-profile-dropdown">
                    <div class="user-avatar">
                        {{ mb_substr(auth('lawyer')->user()->name, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <span class="name">{{ auth('lawyer')->user()->name }}</span>
                        <span class="role">وکیل پایه یک</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-body">
            @yield('content')
        </div>
    </main>

    <div class="flash-wrapper">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
    </div>

    @stack('scripts')
</body>
</html>