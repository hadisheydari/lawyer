<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'دفتر وکالت ابدالی و جوشقانی')</title>

    {{-- <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-body: #fdfbf7;
            --bg-white: #ffffff;
            --gold-main: #c5a059;
            --gold-dark: #9e7f41;
            --gold-light: #e6cfa3;
            --navy: #102a43;
            --navy-dark: #0a1c2e;
            --text-heading: #2c241b;
            --text-body: #595048;
            --shadow-card: 0 10px 30px rgba(0, 0, 0, 0.06);
            --radius-md: 12px;
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
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(253, 251, 247, 0.97);
            backdrop-filter: blur(12px);
            border-bottom: 3px solid var(--gold-main);
            padding: 15px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            font-size: 2rem;
            color: var(--gold-main);
        }

        .brand-text h1 {
            font-size: 1.1rem;
            font-weight: 900;
            color: var(--navy);
            margin: 0;
            line-height: 1.3;
        }

        .brand-text span {
            font-size: 0.75rem;
            color: var(--gold-dark);
            font-weight: 500;
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

        .nav-link:hover,
        .nav-link.active {
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

        .nav-cta {
            background: var(--navy);
            color: #fff !important;
            padding: 10px 22px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .nav-cta:hover {
            background: var(--gold-main);
            color: var(--navy) !important;
        }

        .nav-user-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-login-btn {
            border: 1.5px solid var(--gold-main);
            color: var(--gold-dark);
            padding: 8px 18px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.88rem;
            transition: var(--transition);
        }

        .nav-login-btn:hover {
            background: var(--gold-main);
            color: #fff;
        }

        /* Logout inline form در هدر */
        .nav-logout-btn {
            border: 1.5px solid #fecaca;
            color: #dc2626;
            padding: 8px 18px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.88rem;
            background: none;
            cursor: pointer;
            font-family: 'Vazirmatn', sans-serif;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-logout-btn:hover {
            background: #fef2f2;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            font-size: 1.3rem;
            color: var(--navy);
        }

        /* ─── Page Banner ────────────────────────────────────── */
        .page-banner {
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
            padding: 60px 20px;
            position: relative;
            overflow: hidden;
        }

        .page-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 70% 50%, rgba(197, 160, 89, 0.12), transparent 60%);
        }

        .page-banner-inner {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .page-banner h1 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            color: #fff;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .page-banner .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.88rem;
        }

        .page-banner .breadcrumb a {
            color: var(--gold-main);
        }

        .page-banner .breadcrumb i {
            font-size: 0.7rem;
        }

        /* ─── Footer ─────────────────────────────────────────── */
        .footer {
            background-color: #1a1612;
            color: #888;
            padding: 60px 20px 20px;
            text-align: center;
            border-top: 4px solid var(--gold-main);
            margin-top: 80px;
        }

        .footer-logo {
            font-size: 1.5rem;
            color: #fff;
            font-weight: 900;
            margin-bottom: 10px;
            display: block;
        }

        .footer-tagline {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 30px;
            display: block;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #888;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: var(--gold-main);
        }

        .footer-socials {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .footer-socials a {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .footer-socials a:hover {
            border-color: var(--gold-main);
            color: var(--gold-main);
        }

        .footer-copy {
            font-size: 0.8rem;
            color: #555;
            border-top: 1px solid #2a2520;
            padding-top: 20px;
        }

        /* ─── Flash Messages ─────────────────────────────────── */
        .flash-container {
            max-width: 1200px;
            margin: 20px auto 0;
            padding: 0 20px;
        }

        .flash {
            padding: 14px 20px;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
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

        .flash-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* ─── فرم تماس مدرن (نسخه فلکس‌باکس - بدون باگ) ─── */
        .contact-form-box {
            background: #ffffff;
            padding: 40px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(207, 168, 110, 0.15);
            box-shadow: 0 10px 40px rgba(16, 42, 67, 0.06);
        }

        .form-title {
            font-size: 1.8rem;
            color: var(--text-heading);
            margin-bottom: 10px;
            font-weight: 800;
        }

        .form-desc {
            margin-bottom: 30px;
            color: var(--text-body);
            font-size: 0.95rem;
        }

        .form-group-c {
            margin-bottom: 20px;
        }

        /* باکس اصلی دربرگیرنده آیکون و اینپوت */
        .input-box {
            display: flex;
            align-items: center;
            /* تراز کردن عمودی آیکون و متن */
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 15px;
            /* پدینگ فقط برای چپ و راست باکس */
            transition: all 0.3s ease;
            overflow: hidden;
        }

        /* وقتی کاربر داخل اینپوت کلیک می‌کند، کل باکس تغییر رنگ می‌دهد */
        .input-box:focus-within {
            background-color: #ffffff;
            border-color: var(--gold-main);
            box-shadow: 0 0 0 4px rgba(207, 168, 110, 0.12);
        }

        /* استایل آیکون */
        .input-box i {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-left: 10px;
            /* فاصله آیکون از متن (چون راست‌چین است) */
            transition: all 0.3s ease;
        }

        .input-box:focus-within i {
            color: var(--gold-main);
        }

        /* استایل خود اینپوت */
        .input-box input,
        .input-box textarea {
            flex: 1;
            /* پر کردن تمام فضای باقی‌مانده */
            border: none !important;
            background: transparent !important;
            padding: 15px 0;
            /* پدینگ بالا و پایین متن */
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-heading);
            outline: none !important;
            box-shadow: none !important;
        }

        .input-box input::placeholder,
        .input-box textarea::placeholder {
            color: #94a3b8;
        }

        /* تنظیمات اختصاصی برای تکست‌اریا تا آیکون بالا بماند */
        .input-box.textarea-box {
            align-items: flex-start;
            /* کشیدن آیکون به بالا */
            padding-top: 15px;
            /* فاصله آیکون از سقف */
        }

        .input-box.textarea-box textarea {
            padding: 0;
            /* پدینگ را صفر می‌کنیم چون باکس اصلی پدینگ دارد */
            min-height: 120px;
            resize: vertical;
            line-height: 1.8;
        }

        .form-error {
            color: #ef4444;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hamburger {
                display: block;
            }

            .nav-menu.open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 70px;
                right: 0;
                left: 0;
                background: var(--bg-body);
                padding: 20px;
                border-bottom: 2px solid var(--gold-main);
                gap: 15px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Flash Messages --}}
    @if (session()->hasAny(['success', 'error', 'warning', 'info']))
        <div class="flash-container">
            @if (session('success'))
                <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if (session('warning'))
                <div class="flash flash-warning"><i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                </div>
            @endif
            @if (session('info'))
                <div class="flash" style="background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe;">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                </div>
            @endif
        </div>
    @endif

    <header class="header">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="brand">
                <i class="fas fa-scale-balanced brand-icon"></i>
                <div class="brand-text">
                    <h1>ابدالی و جوشقانی</h1>
                    <span>دفتر وکالت تخصصی</span>
                </div>
            </a>

            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="{{ route('home') }}"
                            class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">صفحه اصلی</a></li>
                    <li><a href="{{ route('about') }}"
                            class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">درباره وکلا</a></li>
                    <li><a href="{{ route('services.index') }}"
                            class="nav-link {{ request()->routeIs('services*') ? 'active' : '' }}">حوزه‌های وکالت</a>
                    </li>
                    <li><a href="{{ route('articles.index') }}"
                            class="nav-link {{ request()->routeIs('articles*') ? 'active' : '' }}">مقالات</a></li>
                    <li><a href="{{ route('calculators.index') }}"
                            class="nav-link {{ request()->routeIs('calculators*') ? 'active' : '' }}">ماشین‌حساب
                            حقوقی</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">تماس با ما</a></li>
                </ul>
            </nav>

            <div class="nav-user-links">
                @auth
                    @if (auth('lawyer')->check())
                        <a href="{{ route('lawyer.dashboard') }}" class="btn">پنل وکیل</a>
                    @elseif(auth()->check())
                        <a href="{{ route('dashboard') }}" class="btn">داشبورد کاربری</a>
                    @endif
                    {{-- ✅ logout با POST form --}}
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="nav-logout-btn">
                            <i class="fas fa-sign-out-alt"></i> خروج
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-login-btn">ورود</a>
                    <a href="{{ route('reserve.index') }}" class="nav-cta">
                        <i class="fas fa-phone-alt"></i> مشاوره فوری
                    </a>
                @endauth

                <span class="hamburger" onclick="toggleMenu()">
                    <i class="fas fa-bars"></i>
                </span>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <span class="footer-logo">
            <i class="fas fa-scale-balanced" style="color:var(--gold-main);margin-left:8px;"></i>ابدالی و جوشقانی
        </span>
        <span class="footer-tagline">دفاع از حق شما، تخصص ماست</span>

        <div class="footer-links">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <a href="{{ route('about') }}">درباره ما</a>
            <a href="{{ route('services.index') }}">خدمات</a>
            <a href="{{ route('articles.index') }}">مقالات</a>
            <a href="{{ route('calculators.index') }}">ماشین‌حساب حقوقی</a>
            <a href="{{ route('contact') }}">تماس با ما</a>
        </div>

        <div class="footer-socials">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-telegram"></i></a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>

        <div class="footer-copy">
            <p>© {{ now()->year }} تمام حقوق محفوظ است. دفتر وکالت ابدالی و جوشقانی</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('open');
        }
    </script>

    @stack('scripts')
</body>

</html>
