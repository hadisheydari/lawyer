<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'دفتر وکالت ابدالی و جوشقانی')</title>

    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ─── Design System ──────────────────────────────────────── */
        :root {
            --bg-body: #fdfbf7;
            --bg-white: #ffffff;
            --gold-main: #cfa86e;
            --gold-dark: #a67c52;
            --navy: #102a43;
            --text-heading: #2c241b;
            --text-body: #595048;
            --shadow-card: 0 15px 35px rgba(0,0,0,0.08);
            --radius-md: 12px;
            --radius-lg: 24px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-body);
            line-height: 1.8;
            overflow-x: hidden;
        }

        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0h-2.828zM43.314 0L49 5.686l-1.414 1.414L40.486 0h2.828zM16.686 0L11 5.686l1.414 1.414L19.514 0h-2.828zM22.344 0L13.858 8.485l1.414 1.415L25.172 0h-2.828zM32 0l-5.657 5.657L22.086 1.414 23.5 0H32zm5.657 0l5.657 5.657L47.914 1.414 46.5 0h-8.5z' fill='%23cfa86e' fill-opacity='0.04' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1; pointer-events: none;
        }

        a { text-decoration: none; color: inherit; transition: var(--transition); }

        /* ─── Header ─────────────────────────────────────────────── */
        .header {
            position: sticky; top: 0; z-index: 1000;
            background: rgba(253,251,247,0.95); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(207,168,110,0.15);
            padding: 0;
        }

        .nav-container {
            max-width: 1200px; margin: 0 auto; padding: 0 20px;
            display: flex; justify-content: space-between; align-items: center;
            height: 72px;
        }

        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-icon { font-size: 2rem; color: var(--gold-main); }
        .brand-text h1 { font-size: 1.15rem; font-weight: 900; color: var(--text-heading); margin: 0; }
        .brand-text span { font-size: 0.72rem; color: var(--gold-dark); letter-spacing: 1px; display: block; }

        .nav-menu { display: flex; gap: 30px; align-items: center; }
        .nav-link {
            font-weight: 500; color: var(--text-heading);
            font-size: 0.92rem; padding: 5px 0; position: relative;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: -2px; right: 0;
            width: 0; height: 2px; background: var(--gold-main);
            transition: 0.3s;
        }
        .nav-link:hover::after,
        .nav-link.active::after { width: 100%; }
        .nav-link:hover, .nav-link.active { color: var(--gold-main); }

        .nav-actions { display: flex; gap: 10px; align-items: center; }

        .btn-header-login {
            padding: 9px 22px; border-radius: var(--radius-md);
            font-weight: 700; font-size: 0.88rem;
            border: 2px solid rgba(207,168,110,0.4);
            color: var(--text-heading); transition: 0.3s;
        }
        .btn-header-login:hover { border-color: var(--gold-main); color: var(--gold-main); }

        .btn-header-cta {
            padding: 9px 22px; border-radius: var(--radius-md);
            font-weight: 700; font-size: 0.88rem;
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            box-shadow: 0 4px 15px rgba(207,168,110,0.35);
            display: flex; align-items: center; gap: 8px;
        }
        .btn-header-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(207,168,110,0.5);
            color: #fff;
        }

        /* Mobile burger */
        .burger {
            display: none; background: none; border: none;
            font-size: 1.5rem; color: var(--text-heading); cursor: pointer;
        }

        /* ─── Page Banner (hero کوچک برای صفحات داخلی) ──────────── */
        .page-banner {
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
            padding: 60px 20px;
            position: relative; overflow: hidden;
        }
        .page-banner::before {
            content: ''; position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle at 70% 50%, rgba(207,168,110,0.12), transparent 60%);
        }
        .page-banner-inner {
            max-width: 1200px; margin: 0 auto;
            position: relative; z-index: 1;
        }
        .page-banner h1 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            color: #fff; font-weight: 900; margin-bottom: 10px;
        }
        .page-banner .breadcrumb {
            display: flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.6); font-size: 0.88rem;
        }
        .page-banner .breadcrumb a { color: var(--gold-main); }
        .page-banner .breadcrumb i { font-size: 0.7rem; }

        /* ─── Footer ─────────────────────────────────────────────── */
        .footer {
            background: #1a1612; color: #888;
            padding: 60px 20px 20px;
            border-top: 4px solid var(--gold-main);
        }
        .footer-grid {
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: 1.5fr 1fr 1fr;
            gap: 50px; padding-bottom: 40px;
            border-bottom: 1px solid #333;
        }
        .footer-brand h3 { font-size: 1.3rem; color: #fff; font-weight: 900; margin-bottom: 12px; }
        .footer-brand p { font-size: 0.88rem; line-height: 1.9; color: #777; }
        .footer-col h4 { color: var(--gold-main); font-size: 0.95rem; margin-bottom: 18px; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a { color: #777; font-size: 0.88rem; transition: 0.3s; }
        .footer-col ul li a:hover { color: var(--gold-main); padding-right: 5px; }
        .footer-bottom {
            max-width: 1200px; margin: 20px auto 0;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.82rem;
        }
        .footer-socials { display: flex; gap: 12px; }
        .footer-socials a {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: center;
            color: #888; font-size: 0.9rem; transition: 0.3s;
        }
        .footer-socials a:hover { background: var(--gold-main); color: #fff; }

        @media (max-width: 900px) {
            .nav-menu { display: none; }
            .burger { display: block; }
            .nav-menu.open {
                display: flex; flex-direction: column;
                position: absolute; top: 72px; right: 0; left: 0;
                background: rgba(253,251,247,0.98); backdrop-filter: blur(12px);
                padding: 20px; gap: 15px; border-bottom: 1px solid rgba(207,168,110,0.2);
                z-index: 999;
            }
            .footer-grid { grid-template-columns: 1fr; gap: 30px; }
            .footer-bottom { flex-direction: column; gap: 15px; text-align: center; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ═══ HEADER ════════════════════════════════════════════════════ --}}
<header class="header" id="mainHeader">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="brand">
            <i class="fas fa-scale-balanced brand-icon"></i>
            <div class="brand-text">
                <h1>ابدالی و جوشقانی</h1>
                <span>دفتر وکالت پایه یک</span>
            </div>
        </a>

        <nav class="nav-menu" id="navMenu">
            <a href="{{ route('home') }}"
               class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
               صفحه اصلی
            </a>
            <a href="{{ route('lawyers.index') }}"
               class="nav-link {{ request()->routeIs('lawyers.*') ? 'active' : '' }}">
               درباره وکلا
            </a>
            <a href="{{ route('services.index') }}"
               class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
               حوزه‌های وکالت
            </a>
            <a href="{{ route('articles.index') }}"
               class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}">
               مقالات
            </a>
            <a href="{{ route('calculators.index') }}"
               class="nav-link {{ request()->routeIs('calculators.*') ? 'active' : '' }}">
               <i class="fas fa-calculator" style="font-size:0.8rem;color:var(--gold-main);"></i>
               محاسبات حقوقی
            </a>
            <a href="{{ route('contact') }}"
               class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
               تماس با ما
            </a>
        </nav>

        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-header-login">ورود</a>
            <a href="{{ route('reserve.index') }}" class="btn-header-cta">
                <i class="fas fa-calendar-check"></i> رزرو نوبت
            </a>
            <button class="burger" id="burger" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>

{{-- ═══ CONTENT ═════════════════════════════════════════════════════ --}}
@yield('content')

{{-- ═══ FOOTER ══════════════════════════════════════════════════════ --}}
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <h3><i class="fas fa-scale-balanced" style="color:var(--gold-main);margin-left:8px;"></i> ابدالی و جوشقانی</h3>
            <p>دفتر وکالت پایه یک دادگستری اصفهان، با بیش از دو دهه سابقه درخشان در دفاع از حقوق موکلین در کلیه مراجع قضایی کشور.</p>
            <div class="footer-socials" style="margin-top:20px;">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-telegram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>دسترسی سریع</h4>
            <ul>
                <li><a href="{{ route('lawyers.index') }}">درباره وکلا</a></li>
                <li><a href="{{ route('services.index') }}">حوزه‌های وکالت</a></li>
                <li><a href="{{ route('articles.index') }}">مقالات حقوقی</a></li>
                <li><a href="{{ route('calculators.index') }}">محاسبات حقوقی</a></li>
                <li><a href="{{ route('contact') }}">تماس با ما</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>اطلاعات تماس</h4>
            <ul>
                <li><a href="tel:09131146888"><i class="fas fa-phone" style="color:var(--gold-main);margin-left:6px;"></i> ۰۹۱۳۱۱۴۶۸۸۸</a></li>
                <li><a href="tel:09132888859"><i class="fas fa-phone" style="color:var(--gold-main);margin-left:6px;"></i> ۰۹۱۳۲۸۸۸۸۵۹</a></li>
                <li><a href="#"><i class="fas fa-envelope" style="color:var(--gold-main);margin-left:6px;"></i> info@abdali-law.ir</a></li>
                <li><a href="#"><i class="fas fa-map-marker-alt" style="color:var(--gold-main);margin-left:6px;"></i> اصفهان، میدان جمهوری</a></li>
                <li><a href="#"><i class="fas fa-clock" style="color:var(--gold-main);margin-left:6px;"></i> شنبه تا چهارشنبه ۱۷–۲۱</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© ۱۴۰۴ تمامی حقوق محفوظ است — دفتر وکالت ابدالی و جوشقانی</span>
        <span>طراحی و توسعه با <i class="fas fa-heart" style="color:#e74c3c;"></i></span>
    </div>
</footer>

<script>
function toggleMenu() {
    const menu = document.getElementById('navMenu');
    menu.classList.toggle('open');
}
// Close menu on outside click
document.addEventListener('click', e => {
    const menu = document.getElementById('navMenu');
    const burger = document.getElementById('burger');
    if (!menu.contains(e.target) && !burger.contains(e.target)) {
        menu.classList.remove('open');
    }
});
</script>

@stack('scripts')
</body>
</html>
