@extends('layouts.public')
@section('title', 'دفتر وکالت ابدالی و جوشقانی | اصفهان')

@push('styles')
<style>
    /* ─── Hero ──────────────────────────────────────────────────── */
    .hero {
        min-height: 92vh;
        background: linear-gradient(135deg, #0a1c2e 0%, #102a43 60%, #1a3a58 100%);
        display: flex; align-items: center;
        position: relative; overflow: hidden; padding: 80px 20px;
    }
    .hero::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 75% 50%, rgba(207,168,110,0.1) 0%, transparent 60%),
            radial-gradient(ellipse at 20% 80%, rgba(207,168,110,0.05) 0%, transparent 40%);
    }
    .hero-inner {
        max-width: 1200px; margin: 0 auto; width: 100%;
        display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
        position: relative; z-index: 1;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(207,168,110,0.15); border: 1px solid rgba(207,168,110,0.3);
        color: var(--gold-main); padding: 8px 18px; border-radius: 50px;
        font-size: 0.8rem; font-weight: 700; margin-bottom: 24px;
        letter-spacing: 0.5px;
    }
    .hero h1 {
        font-size: clamp(2.2rem, 4vw, 3.2rem);
        color: #fff; font-weight: 900; line-height: 1.35;
        margin-bottom: 20px;
    }
    .hero h1 em {
        font-style: normal; color: var(--gold-main);
        display: block;
    }
    .hero p {
        color: rgba(255,255,255,0.65); font-size: 1.05rem;
        line-height: 1.9; margin-bottom: 40px;
    }
    .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }
    .btn-hero-primary {
        padding: 16px 36px; border-radius: 14px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; font-weight: 800; font-size: 1rem;
        box-shadow: 0 8px 25px rgba(207,168,110,0.4);
        display: flex; align-items: center; gap: 10px; transition: 0.3s;
    }
    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(207,168,110,0.55);
        color: #fff;
    }
    .btn-hero-secondary {
        padding: 16px 36px; border-radius: 14px;
        border: 2px solid rgba(255,255,255,0.2);
        color: #fff; font-weight: 700; font-size: 1rem;
        display: flex; align-items: center; gap: 10px; transition: 0.3s;
    }
    .btn-hero-secondary:hover {
        border-color: var(--gold-main); color: var(--gold-main);
        background: rgba(207,168,110,0.05);
    }
    .hero-stats {
        display: flex; gap: 30px; margin-top: 50px;
        padding-top: 40px; border-top: 1px solid rgba(255,255,255,0.08);
    }
    .hero-stat { text-align: center; }
    .hero-stat .num { font-size: 1.9rem; font-weight: 900; color: var(--gold-main); display: block; }
    .hero-stat .lbl { font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-top: 4px; display: block; }

    /* کارت وکلا در hero */
    .hero-lawyers { display: flex; flex-direction: column; gap: 20px; }
    .hero-lawyer-card {
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px; padding: 28px; backdrop-filter: blur(10px);
        display: flex; gap: 22px; align-items: center;
        transition: 0.4s;
    }
    .hero-lawyer-card:hover {
        background: rgba(255,255,255,0.08);
        border-color: rgba(207,168,110,0.3);
        transform: translateX(-5px);
    }
    .hero-lawyer-avatar {
        width: 80px; height: 80px; border-radius: 16px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: #fff; font-weight: 900;
        overflow: hidden;
    }
    .hero-lawyer-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .hero-lawyer-info { flex: 1; }
    .hero-lawyer-info h3 { color: #fff; font-weight: 800; font-size: 1.05rem; margin-bottom: 4px; }
    .hero-lawyer-info span { color: var(--gold-main); font-size: 0.78rem; display: block; margin-bottom: 10px; }
    .hero-lawyer-tags { display: flex; gap: 6px; flex-wrap: wrap; }
    .hero-lawyer-tag {
        font-size: 0.72rem; padding: 3px 10px; border-radius: 20px;
        background: rgba(207,168,110,0.12); color: rgba(255,255,255,0.6); border: 1px solid rgba(207,168,110,0.2);
    }
    .hero-lawyer-meta {
        display: flex; flex-direction: column; gap: 10px; align-items: flex-end;
    }
    .hero-lawyer-meta .rating { color: var(--gold-main); font-size: 0.85rem; font-weight: 800; }
    .hero-lawyer-meta a {
        font-size: 0.78rem; color: rgba(255,255,255,0.5);
        border: 1px solid rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 8px;
        transition: 0.3s; white-space: nowrap;
    }
    .hero-lawyer-meta a:hover { border-color: var(--gold-main); color: var(--gold-main); }

    /* ─── Trust Bar ────────────────────────────────────────────── */
    .trust-bar {
        background: var(--navy);
        padding: 20px;
    }
    .trust-bar-inner {
        max-width: 1200px; margin: 0 auto;
        display: flex; justify-content: space-around; align-items: center;
        gap: 20px; flex-wrap: wrap;
    }
    .trust-item {
        display: flex; align-items: center; gap: 12px;
        color: rgba(255,255,255,0.7); font-size: 0.85rem;
    }
    .trust-item i { color: var(--gold-main); font-size: 1.2rem; }
    .trust-item strong { color: #fff; }

    /* ─── Services Teaser ──────────────────────────────────────── */
    .services-teaser { padding: 100px 20px; background: var(--bg-body); }
    .section-header { text-align: center; margin-bottom: 60px; }
    .section-label {
        font-size: 0.78rem; font-weight: 800; letter-spacing: 2px;
        color: var(--gold-main); text-transform: uppercase; display: block;
        margin-bottom: 12px;
    }
    .section-header h2 {
        font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: var(--text-heading);
        margin-bottom: 14px;
    }
    .section-header p { color: var(--text-body); font-size: 1rem; max-width: 550px; margin: 0 auto; }
    .services-teaser-inner { max-width: 1200px; margin: 0 auto; }
    .services-grid-home {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px;
        margin-bottom: 40px;
    }
    .srv-card {
        background: #fff; border-radius: 20px; padding: 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.04);
        transition: 0.35s; text-decoration: none; color: inherit;
        display: flex; flex-direction: column; gap: 16px;
    }
    .srv-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.09);
        border-color: rgba(207,168,110,0.3);
    }
    .srv-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: var(--gold-main);
        transition: 0.3s;
    }
    .srv-card:hover .srv-icon {
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff;
    }
    .srv-card h3 { font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin: 0; }
    .srv-card p { font-size: 0.85rem; color: var(--text-body); line-height: 1.75; margin: 0; }
    .srv-link {
        color: var(--gold-main); font-size: 0.82rem; font-weight: 700;
        display: flex; align-items: center; gap: 6px; margin-top: auto;
        transition: 0.3s;
    }
    .srv-card:hover .srv-link { gap: 10px; }
    .section-cta { text-align: center; }
    .btn-outline-gold {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 14px 36px; border-radius: 14px;
        border: 2px solid var(--gold-main); color: var(--gold-main);
        font-weight: 700; font-size: 0.95rem; transition: 0.3s;
    }
    .btn-outline-gold:hover { background: var(--gold-main); color: #fff; }

    /* ─── Why Us ───────────────────────────────────────────────── */
    .why-us { padding: 100px 20px; background: #fff; }
    .why-us-inner { max-width: 1200px; margin: 0 auto; }
    .why-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
    }
    .why-text h2 {
        font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 900; color: var(--text-heading);
        margin-bottom: 20px; line-height: 1.4;
    }
    .why-text p { color: var(--text-body); font-size: 0.95rem; line-height: 1.9; margin-bottom: 30px; }
    .why-list { display: flex; flex-direction: column; gap: 18px; margin-bottom: 40px; }
    .why-item {
        display: flex; gap: 16px; align-items: flex-start;
    }
    .why-item-icon {
        width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
        background: linear-gradient(135deg, rgba(207,168,110,0.12), rgba(166,124,82,0.12));
        display: flex; align-items: center; justify-content: center;
        color: var(--gold-dark); font-size: 1.1rem;
    }
    .why-item-text h4 { font-size: 0.95rem; font-weight: 800; color: var(--text-heading); margin-bottom: 4px; }
    .why-item-text p { font-size: 0.84rem; color: var(--text-body); line-height: 1.7; margin: 0; }
    .why-visual {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
    }
    .why-stat-box {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        border-radius: 24px; padding: 35px 25px; text-align: center;
        color: #fff;
    }
    .why-stat-box.gold {
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
    }
    .why-stat-box.span-2 { grid-column: span 2; }
    .why-stat-box .big { font-size: 2.5rem; font-weight: 900; display: block; color: var(--gold-main); }
    .why-stat-box.gold .big { color: #fff; }
    .why-stat-box .desc { font-size: 0.82rem; color: rgba(255,255,255,0.65); margin-top: 6px; display: block; }
    .why-stat-box .icon-big { font-size: 2.2rem; color: var(--gold-main); margin-bottom: 12px; display: block; }
    .why-stat-box.gold .icon-big { color: rgba(255,255,255,0.8); }

    /* ─── Articles Teaser ──────────────────────────────────────── */
    .articles-teaser { padding: 100px 20px; background: var(--bg-body); }
    .articles-teaser-inner { max-width: 1200px; margin: 0 auto; }
    .articles-grid-home {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;
        margin-bottom: 40px;
    }
    .art-card {
        background: #fff; border-radius: 20px; overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.04);
        transition: 0.35s; text-decoration: none; color: inherit;
        display: flex; flex-direction: column;
    }
    .art-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(0,0,0,0.1);
    }
    .art-thumb {
        height: 180px;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: var(--gold-main); position: relative; overflow: hidden;
    }
    .art-thumb::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0;
        height: 50%; background: linear-gradient(to top, rgba(10,28,46,0.7), transparent);
    }
    .art-cat {
        position: absolute; bottom: 14px; right: 14px; z-index: 1;
        background: rgba(207,168,110,0.95); color: #fff; padding: 4px 12px;
        border-radius: 20px; font-size: 0.72rem; font-weight: 700;
    }
    .art-body { padding: 24px; flex: 1; display: flex; flex-direction: column; gap: 12px; }
    .art-meta { display: flex; gap: 14px; font-size: 0.75rem; color: #bbb; }
    .art-meta span { display: flex; align-items: center; gap: 5px; }
    .art-body h3 {
        font-size: 1rem; font-weight: 800; color: var(--text-heading);
        line-height: 1.55; margin: 0;
    }
    .art-link {
        color: var(--gold-main); font-size: 0.8rem; font-weight: 700;
        display: flex; align-items: center; gap: 6px; margin-top: auto;
    }

    /* ─── CTA Section ──────────────────────────────────────────── */
    .cta-section {
        padding: 100px 20px;
        background: linear-gradient(135deg, var(--navy) 0%, #1a3a58 100%);
        position: relative; overflow: hidden;
    }
    .cta-section::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 50% 50%, rgba(207,168,110,0.08) 0%, transparent 70%);
    }
    .cta-inner {
        max-width: 700px; margin: 0 auto; text-align: center; position: relative; z-index: 1;
    }
    .cta-inner h2 {
        font-size: clamp(1.8rem, 3vw, 2.6rem); font-weight: 900; color: #fff;
        margin-bottom: 18px; line-height: 1.4;
    }
    .cta-inner p { color: rgba(255,255,255,0.6); font-size: 1rem; margin-bottom: 40px; line-height: 1.8; }
    .cta-actions { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
    .btn-cta-gold {
        padding: 17px 40px; border-radius: 14px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; font-weight: 800; font-size: 1rem;
        box-shadow: 0 8px 25px rgba(207,168,110,0.4);
        display: flex; align-items: center; gap: 10px; transition: 0.3s;
    }
    .btn-cta-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 35px rgba(207,168,110,0.55);
        color: #fff;
    }
    .btn-cta-outline {
        padding: 17px 40px; border-radius: 14px;
        border: 2px solid rgba(255,255,255,0.25);
        color: #fff; font-weight: 700; font-size: 1rem;
        display: flex; align-items: center; gap: 10px; transition: 0.3s;
    }
    .btn-cta-outline:hover { border-color: var(--gold-main); color: var(--gold-main); background: rgba(207,168,110,0.05); }

    /* Responsive */
    @media (max-width: 1024px) {
        .hero-inner { grid-template-columns: 1fr; gap: 50px; }
        .hero-lawyers { flex-direction: row; }
        .why-grid { grid-template-columns: 1fr; }
        .why-visual { order: -1; }
    }
    @media (max-width: 768px) {
        .services-grid-home { grid-template-columns: 1fr; }
        .articles-grid-home { grid-template-columns: 1fr; }
        .hero-lawyers { flex-direction: column; }
        .hero-stats { gap: 20px; }
        .trust-bar-inner { gap: 14px; }
    }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═════════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-award"></i>
                وکلای پایه یک دادگستری اصفهان
            </div>
            <h1>
                حق شما را
                <em>با تجربه و اقتدار</em>
                می‌گیریم
            </h1>
            <p>
                دفتر وکالت ابدالی و جوشقانی با بیش از دو دهه سابقه درخشان، در کنار شما
                در دشوارترین مسائل حقوقی ایستاده‌ایم. از مشاوره تا اجرای حکم، تمام مسیر را
                با خیال آسوده طی کنید.
            </p>
            <div class="hero-actions">
                <a href="{{ route('reserve.index') }}" class="btn-hero-primary">
                    <i class="fas fa-calendar-check"></i>
                    رزرو مشاوره رایگان
                </a>
                <a href="{{ route('services.index') }}" class="btn-hero-secondary">
                    <i class="fas fa-balance-scale"></i>
                    حوزه‌های تخصصی
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="num">+۲۸</span>
                    <span class="lbl">سال تجربه</span>
                </div>
                <div class="hero-stat">
                    <span class="num">+۲۰۰۰</span>
                    <span class="lbl">پرونده موفق</span>
                </div>
                <div class="hero-stat">
                    <span class="num">۹۸٪</span>
                    <span class="lbl">رضایت موکلین</span>
                </div>
                <div class="hero-stat">
                    <span class="num">۲۴/۷</span>
                    <span class="lbl">پشتیبانی</span>
                </div>
            </div>
        </div>

        <div class="hero-lawyers">
            <a href="{{ route('lawyers.show', 'babak') }}" class="hero-lawyer-card">
                <div class="hero-lawyer-avatar">ب</div>
                <div class="hero-lawyer-info">
                    <h3>بابک ابدالی</h3>
                    <span>وکیل پایه یک دادگستری</span>
                    <div class="hero-lawyer-tags">
                        <span class="hero-lawyer-tag">تجاری</span>
                        <span class="hero-lawyer-tag">ملکی</span>
                        <span class="hero-lawyer-tag">کیفری</span>
                        <span class="hero-lawyer-tag">اداری</span>
                    </div>
                </div>
                <div class="hero-lawyer-meta">
                    <span class="rating"><i class="fas fa-star"></i> ۹۷٪</span>
                    <span>۲۸ سال سابقه</span>
                </div>
            </a>
            <a href="{{ route('lawyers.show', 'zahra') }}" class="hero-lawyer-card">
                <div class="hero-lawyer-avatar">ز</div>
                <div class="hero-lawyer-info">
                    <h3>زهرا جوشقانی</h3>
                    <span>وکیل پایه یک دادگستری</span>
                    <div class="hero-lawyer-tags">
                        <span class="hero-lawyer-tag">خانواده</span>
                        <span class="hero-lawyer-tag">ارث</span>
                        <span class="hero-lawyer-tag">نفقه</span>
                        <span class="hero-lawyer-tag">حضانت</span>
                    </div>
                </div>
                <div class="hero-lawyer-meta">
                    <span class="rating"><i class="fas fa-star"></i> ۹۹٪</span>
                    <span>۲۰ سال سابقه</span>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- ═══ TRUST BAR ══════════════════════════════════════════════════ --}}
<div class="trust-bar">
    <div class="trust-bar-inner">
        <div class="trust-item">
            <i class="fas fa-shield-halved"></i>
            <span><strong>محرمانگی</strong> کامل اطلاعات</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-handshake"></i>
            <span><strong>مشاوره اولیه</strong> رایگان</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-clock"></i>
            <span>پاسخگویی <strong>۲۴ ساعته</strong></span>
        </div>
        <div class="trust-item">
            <i class="fas fa-location-dot"></i>
            <span>اصفهان، <strong>میدان جمهوری</strong></span>
        </div>
        <div class="trust-item">
            <i class="fas fa-certificate"></i>
            <span>عضو <strong>کانون وکلا</strong></span>
        </div>
    </div>
</div>

{{-- ═══ SERVICES TEASER ════════════════════════════════════════════ --}}
<section class="services-teaser">
    <div class="services-teaser-inner">
        <div class="section-header">
            <span class="section-label">حوزه‌های تخصصی</span>
            <h2>در هر مسئله حقوقی، ما پاسخ داریم</h2>
            <p>از دعاوی خانوادگی تا پرونده‌های پیچیده تجاری، تیم متخصص ما آماده دفاع از حقوق شماست</p>
        </div>
        <div class="services-grid-home">
            <a href="{{ route('services.show', 'family') }}" class="srv-card">
                <div class="srv-icon"><i class="fas fa-heart"></i></div>
                <h3>حقوق خانواده</h3>
                <p>طلاق، مهریه، حضانت فرزند، نفقه و اجرت‌المثل با رویکردی انسانی و محرمانه</p>
                <span class="srv-link">مشاهده بیشتر <i class="fas fa-arrow-left"></i></span>
            </a>
            <a href="{{ route('services.show', 'commercial') }}" class="srv-card">
                <div class="srv-icon"><i class="fas fa-briefcase"></i></div>
                <h3>دعاوی تجاری</h3>
                <p>وصول مطالبات، اختلافات شرکتی، چک صیادی و ورشکستگی با تجربه دو دهه</p>
                <span class="srv-link">مشاهده بیشتر <i class="fas fa-arrow-left"></i></span>
            </a>
            <a href="{{ route('services.show', 'real-estate') }}" class="srv-card">
                <div class="srv-icon"><i class="fas fa-city"></i></div>
                <h3>دعاوی ملکی</h3>
                <p>خلع ید، الزام به تنظیم سند، ابطال معامله و تمام اختلافات ملکی</p>
                <span class="srv-link">مشاهده بیشتر <i class="fas fa-arrow-left"></i></span>
            </a>
            <a href="{{ route('services.show', 'inheritance') }}" class="srv-card">
                <div class="srv-icon"><i class="fas fa-scroll"></i></div>
                <h3>ارث و ترکه</h3>
                <p>انحصار وراثت، تقسیم ترکه، ابطال وصیت‌نامه و مطالبه سهم‌الارث</p>
                <span class="srv-link">مشاهده بیشتر <i class="fas fa-arrow-left"></i></span>
            </a>
            <a href="{{ route('services.show', 'criminal') }}" class="srv-card">
                <div class="srv-icon"><i class="fas fa-gavel"></i></div>
                <h3>دعاوی کیفری</h3>
                <p>دفاع از متهمان و احقاق حق شاکیان در کلاهبرداری، خیانت در امانت و جعل</p>
                <span class="srv-link">مشاهده بیشتر <i class="fas fa-arrow-left"></i></span>
            </a>
            <a href="{{ route('services.show', 'administrative') }}" class="srv-card">
                <div class="srv-icon"><i class="fas fa-building"></i></div>
                <h3>حقوق اداری</h3>
                <p>دیوان عدالت اداری، شکایت از شهرداری و کمیسیون‌های دولتی</p>
                <span class="srv-link">مشاهده بیشتر <i class="fas fa-arrow-left"></i></span>
            </a>
        </div>
        <div class="section-cta">
            <a href="{{ route('services.index') }}" class="btn-outline-gold">
                مشاهده همه حوزه‌های تخصصی <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</section>

{{-- ═══ WHY US ═════════════════════════════════════════════════════ --}}
<section class="why-us">
    <div class="why-us-inner">
        <div class="why-grid">
            <div class="why-text">
                <span class="section-label">چرا ما؟</span>
                <h2>وقتی حق شماست، سکوت گزینه نیست</h2>
                <p>
                    انتخاب وکیل مناسب، تفاوت بین پیروزی و شکست در یک پرونده است.
                    دفتر ابدالی و جوشقانی با ترکیب دانش حقوقی عمیق و تجربه میدانی،
                    در کنار شما می‌ایستد.
                </p>
                <div class="why-list">
                    <div class="why-item">
                        <div class="why-item-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="why-item-text">
                            <h4>وکلای متخصص و باتجربه</h4>
                            <p>هر وکیل در حوزه‌ای خاص متخصص است تا بهترین دفاع ممکن انجام شود</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-item-icon"><i class="fas fa-lock"></i></div>
                        <div class="why-item-text">
                            <h4>محرمانگی کامل</h4>
                            <p>اطلاعات پرونده شما در کمال امانت نگه داشته می‌شود</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-item-icon"><i class="fas fa-comments"></i></div>
                        <div class="why-item-text">
                            <h4>ارتباط مستقیم با وکیل</h4>
                            <p>بدون واسطه، مستقیم با وکیل پرونده‌تان در ارتباط هستید</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-item-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="why-item-text">
                            <h4>شفافیت کامل در روند پرونده</h4>
                            <p>در هر مرحله از روند پرونده‌تان مطلع هستید</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('lawyers.index') }}" class="btn-outline-gold">
                    بیشتر درباره ما بدانید <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="why-visual">
                <div class="why-stat-box">
                    <span class="icon-big"><i class="fas fa-scale-balanced"></i></span>
                    <span class="big">+۱۲۰۰</span>
                    <span class="desc">پرونده موفق بابک ابدالی</span>
                </div>
                <div class="why-stat-box">
                    <span class="icon-big"><i class="fas fa-heart"></i></span>
                    <span class="big">+۸۰۰</span>
                    <span class="desc">پرونده موفق زهرا جوشقانی</span>
                </div>
                <div class="why-stat-box gold span-2">
                    <span class="icon-big"><i class="fas fa-star"></i></span>
                    <span class="big">۹۸٪</span>
                    <span class="desc">میانگین رضایت موکلین در طول سال‌های فعالیت</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ ARTICLES TEASER ════════════════════════════════════════════ --}}
<section class="articles-teaser">
    <div class="articles-teaser-inner">
        <div class="section-header">
            <span class="section-label">مقالات حقوقی</span>
            <h2>دانش حقوقی رایگان برای شما</h2>
            <p>مطالب آموزشی کاربردی که به شما کمک می‌کند حقوق خود را بهتر بشناسید</p>
        </div>
        <div class="articles-grid-home">
            <a href="{{ route('articles.show', 'mahrieh-1404') }}" class="art-card">
                <div class="art-thumb">
                    <i class="fas fa-heart"></i>
                    <span class="art-cat">حقوق خانواده</span>
                </div>
                <div class="art-body">
                    <div class="art-meta">
                        <span><i class="fas fa-calendar"></i> ۱۴۰۴/۰۲/۱۲</span>
                        <span><i class="fas fa-clock"></i> ۸ دقیقه</span>
                    </div>
                    <h3>راهنمای جامع مطالبه مهریه در سال ۱۴۰۴</h3>
                    <span class="art-link">خواندن مقاله <i class="fas fa-arrow-left"></i></span>
                </div>
            </a>
            <a href="{{ route('articles.show', 'sayyadi-check') }}" class="art-card">
                <div class="art-thumb">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="art-cat">چک و برات</span>
                </div>
                <div class="art-body">
                    <div class="art-meta">
                        <span><i class="fas fa-calendar"></i> ۱۴۰۴/۰۲/۰۵</span>
                        <span><i class="fas fa-clock"></i> ۵ دقیقه</span>
                    </div>
                    <h3>قانون جدید چک‌های صیادی و روش رفع سوءاثر</h3>
                    <span class="art-link">خواندن مقاله <i class="fas fa-arrow-left"></i></span>
                </div>
            </a>
            <a href="{{ route('articles.show', 'dieh-1404') }}" class="art-card">
                <div class="art-thumb">
                    <i class="fas fa-gavel"></i>
                    <span class="art-cat">حقوق کیفری</span>
                </div>
                <div class="art-body">
                    <div class="art-meta">
                        <span><i class="fas fa-calendar"></i> ۱۴۰۴/۰۱/۱۵</span>
                        <span><i class="fas fa-clock"></i> ۴ دقیقه</span>
                    </div>
                    <h3>دیه سال ۱۴۰۴: مبلغ رسمی و نحوه محاسبه</h3>
                    <span class="art-link">خواندن مقاله <i class="fas fa-arrow-left"></i></span>
                </div>
            </a>
        </div>
        <div class="section-cta">
            <a href="{{ route('articles.index') }}" class="btn-outline-gold">
                همه مقالات <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</section>

{{-- ═══ CTA ════════════════════════════════════════════════════════ --}}
<section class="cta-section">
    <div class="cta-inner">
        <h2>همین الان مشاوره رایگان بگیرید</h2>
        <p>
            اولین قدم برای حل مسئله حقوقی‌تان، صحبت با یک متخصص است.
            ما آماده شنیدن شما هستیم.
        </p>
        <div class="cta-actions">
            <a href="{{ route('reserve.index') }}" class="btn-cta-gold">
                <i class="fas fa-calendar-check"></i>
                رزرو نوبت آنلاین
            </a>
            <a href="tel:09131146888" class="btn-cta-outline">
                <i class="fas fa-phone"></i>
                ۰۹۱۳۱۱۴۶۸۸۸
            </a>
        </div>
    </div>
</section>

@endsection