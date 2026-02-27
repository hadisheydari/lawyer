@extends('layouts.public')

@section('title', 'دفتر وکالت ابدالی و جوشقانی | تجربه و اصالت')

@push('styles')
    <style>
        /* ─── Design System ────────────────────────────────────────────────────── */
        :root {
            --gold-main: #cfa86e;
            --gold-dark: #a67c52;
            --text-heading: #2c241b;
            --text-body: #595048;
            --shadow-soft: 0 10px 40px -10px rgba(44, 36, 27, 0.1);
            --shadow-card: 0 15px 35px rgba(0, 0, 0, 0.08);
            --radius-md: 12px;
            --radius-lg: 24px;
            --radius-arch: 200px 200px 20px 20px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* ─── Buttons ──────────────────────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 32px;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-family: 'Vazirmatn', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold-main) 0%, var(--gold-dark) 100%);
            color: #fff;
            box-shadow: 0 8px 20px rgba(207, 168, 110, 0.4);
            text-decoration: none;
        }

        .btn-primary:hover {
            box-shadow: 0 12px 25px rgba(207, 168, 110, 0.6);
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-heading);
            border: 2px solid var(--gold-main);
            text-decoration: none;
        }

        .btn-outline:hover {
            background: var(--gold-main);
            color: #fff;
        }

        /* ─── HERO ─────────────────────────────────────────────────────────────── */
        .hero {
            position: relative;
            padding: 80px 0 160px;
            overflow: hidden;
            background:
                linear-gradient(90deg, rgba(253, 251, 247, 1) 35%, rgba(253, 251, 247, 0.75) 100%),
                url('https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            border-bottom: 1px solid rgba(207, 168, 110, 0.2);
        }

        .hero-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            align-items: center;
            gap: 40px;
            position: relative;
            z-index: 2;
        }

        .hero-content h2 {
            font-size: 1.1rem;
            color: var(--gold-main);
            font-weight: 800;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-content h2::before {
            content: '';
            width: 40px;
            height: 2px;
            background: var(--gold-main);
        }

        .hero-content .main-title {
            font-size: clamp(2.2rem, 4vw, 3.5rem);
            font-weight: 900;
            color: var(--text-heading);
            line-height: 1.3;
            margin-bottom: 25px;
        }

        .hero-content .main-title span {
            color: var(--gold-main);
        }

        .hero-content p {
            font-size: 1.05rem;
            margin-bottom: 40px;
            max-width: 500px;
            text-align: justify;
            color: #444;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* ─── Hero Visual (دو عکس محرابی) ─────────────────────────────────────── */
        .hero-visual {
            display: flex;
            justify-content: center;
            gap: 25px;
            position: relative;
        }

        .hero-pattern-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 650px;
            height: 650px;
            border: 1px dashed rgba(207, 168, 110, 0.3);
            border-radius: 50%;
            z-index: -1;
            pointer-events: none;
        }

        .hero-img-frame {
            position: relative;
            width: 260px;
            height: 400px;
            border-radius: var(--radius-arch);
            overflow: hidden;
            border: 6px solid #fff;
            outline: 1px solid rgba(207, 168, 110, 0.3);
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            background-color: #f5f0ea;
        }

        .hero-img-frame:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            outline-color: var(--gold-main);
        }

        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: contrast(1.05) saturate(0.9);
        }

        .img-label {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 15px;
            text-align: center;
            font-weight: 700;
            color: var(--text-heading);
            transform: translateY(100%);
            transition: 0.3s;
            border-top: 2px solid var(--gold-main);
        }

        .hero-img-frame:hover .img-label {
            transform: translateY(0);
        }

        /* ─── Stats Bar ────────────────────────────────────────────────────────── */
        .stats-wrapper {
            max-width: 1100px;
            margin: -100px auto 0;
            position: relative;
            z-index: 10;
            padding: 0 20px;
        }

        .stats-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: var(--shadow-card);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--gold-main), var(--gold-dark));
        }

        .stat-item {
            text-align: center;
            position: relative;
        }

        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            height: 70%;
            width: 1px;
            background: rgba(0, 0, 0, 0.05);
        }

        .stat-number {
            display: block;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-body);
            font-weight: 500;
        }

        /* ─── Services ─────────────────────────────────────────────────────────── */
        .services {
            padding: 120px 20px 100px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            color: var(--text-heading);
            font-weight: 900;
            margin-bottom: 15px;
        }

        .section-header p {
            color: var(--text-body);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
        }

        .service-card {
            height: 400px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: relative;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .service-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
            opacity: 0.95;
        }

        .service-card:hover img {
            transform: scale(1.1);
            opacity: 1;
        }

        .service-content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(23, 19, 14, 0.95) 0%, rgba(23, 19, 14, 0.5) 40%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding: 30px;
            z-index: 2;
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: var(--transition);
        }

        .service-icon i {
            color: var(--gold-main);
            font-size: 1.5rem;
        }

        .service-card:hover .service-icon {
            background: var(--gold-main);
            border-color: var(--gold-main);
            transform: translateY(-5px);
        }

        .service-card:hover .service-icon i {
            color: #fff;
        }

        .service-title {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .service-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
            height: 0;
        }

        .service-card:hover .service-desc {
            opacity: 1;
            transform: translateY(0);
            height: auto;
            margin-top: 10px;
        }

        /* ─── Articles ─────────────────────────────────────────────────────────── */
        .articles {
            padding: 50px 20px 100px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .article-card {
            background: #fff;
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .article-card:hover {
            transform: translateY(-10px);
            border-color: var(--gold-main);
        }

        .article-img-box {
            height: 220px;
            position: relative;
            overflow: hidden;
        }

        .article-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .article-card:hover .article-img {
            transform: scale(1.1);
        }

        .article-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .article-meta {
            font-size: 0.8rem;
            color: var(--gold-dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
        }

        .article-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 12px;
            line-height: 1.5;
            transition: 0.3s;
        }

        .article-card:hover .article-title {
            color: var(--gold-main);
        }

        .article-excerpt {
            font-size: 0.9rem;
            color: var(--text-body);
            margin-bottom: 20px;
            line-height: 1.7;
        }

        .read-more {
            margin-top: auto;
            color: var(--gold-main);
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }

        .read-more:hover {
            gap: 10px;
        }

        /* ─── Contact ──────────────────────────────────────────────────────────── */
        .contact-section {
            padding: 80px 20px;
            background-color: #fff;
            position: relative;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 40px;
            align-items: stretch;
        }

        .contact-info-box {
            background-color: var(--text-heading);
            color: #fff;
            padding: 50px 40px;
            border-radius: var(--radius-lg);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-card);
        }

        .contact-info-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--gold-main), var(--gold-dark));
        }

        .info-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: var(--gold-main);
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 30px;
        }

        .info-item i {
            color: var(--gold-main);
            font-size: 1.4rem;
            margin-top: 5px;
        }

        .info-text h4 {
            font-size: 1.05rem;
            margin-bottom: 5px;
            color: #fff;
        }

        .info-text p {
            color: #aaa;
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .contact-form-box {
            background: #fdfbf7;
            padding: 50px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(0, 0, 0, 0.05);
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
        }

        .form-group-c {
            margin-bottom: 20px;
        }

        .form-input-c {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: var(--radius-md);
            font-family: 'Vazirmatn', sans-serif;
            font-size: 1rem;
            background: #fff;
            transition: 0.3s;
        }

        .form-input-c:focus {
            border-color: var(--gold-main);
            outline: none;
            box-shadow: 0 0 0 4px rgba(207, 168, 110, 0.1);
        }

        textarea.form-input-c {
            resize: vertical;
            min-height: 120px;
        }

        /* ─── Calculator CTA Banner ─────────────────────────────────────────────── */
        .calc-banner {
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 0 20px;
        }

        .calc-banner-inner {
            background: linear-gradient(135deg, #102a43 0%, #0a1c2e 100%);
            border-radius: var(--radius-lg);
            padding: 50px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            position: relative;
            overflow: hidden;
        }

        .calc-banner-inner::before {
            content: '§';
            position: absolute;
            left: 40px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12rem;
            color: rgba(207, 168, 110, 0.06);
            font-family: serif;
            line-height: 1;
            pointer-events: none;
        }

        .calc-banner-inner::after {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(207, 168, 110, 0.15), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .calc-banner-text {
            position: relative;
            z-index: 1;
        }

        .calc-banner-text h3 {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .calc-banner-text p {
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.9rem;
            line-height: 1.8;
            max-width: 500px;
        }

        .calc-banner-chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .calc-chip {
            background: rgba(207, 168, 110, 0.15);
            border: 1px solid rgba(207, 168, 110, 0.3);
            color: var(--gold-main);
            padding: 4px 14px;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .calc-banner-btn {
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            padding: 16px 35px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(207, 168, 110, 0.35);
        }

        .calc-banner-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(207, 168, 110, 0.5);
            color: #fff;
        }


        /* ─── Responsive ────────────────────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 40px;
            }

            .hero-content h2 {
                justify-content: center;
            }

            .hero-content h2::before {
                display: none;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-content p {
                margin: 0 auto 40px;
            }

            .hero-visual {
                flex-wrap: wrap;
            }

            .hero-pattern-circle {
                display: none;
            }

            .calc-banner-inner {
                flex-direction: column;
                text-align: center;
                padding: 40px;
            }

            .calc-banner-chips {
                justify-content: center;
            }

            .calc-banner-text p {
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }

            .stats-card {
                grid-template-columns: 1fr 1fr;
                gap: 25px;
                padding: 25px;
            }

            .stat-item:not(:last-child)::after {
                display: none;
            }

            .hero-img-frame {
                width: 200px;
                height: 300px;
            }
        }

        @media (max-width: 600px) {
            .hero-visual {
                flex-direction: column;
                align-items: center;
            }

            .contact-form-box,
            .contact-info-box {
                padding: 30px 20px;
            }

            .stats-wrapper {
                margin-top: -60px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ═══════════════════════════════════════════════════════════
    HERO
    ═══════════════════════════════════════════════════════════ --}}
    <section class="hero">
        <div class="hero-container">

            {{-- متن سمت راست --}}
            <div class="hero-content">
                <h2>تجربه مشترک، نتیجه تضمینی</h2>
                <h1 class="main-title">
                    دفاع از حق شما<br>
                    <span>تخصص ماست</span>
                </h1>
                <p>
                    موسسه حقوقی بابک ابدالی و زهرا جوشقانی، با بیش از دو دهه سابقه درخشان در مراجع
                    قضایی اصفهان. ما مسیرهای پیچیده قانونی را برای شما هموار می‌کنیم.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('reserve.index') }}" class="btn btn-primary">
                        <i class="fas fa-phone-alt"></i> درخواست مشاوره
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline">
                        سوابق کاری
                    </a>
                </div>
            </div>

            {{-- عکس‌های دو وکیل --}}
            <div class="hero-visual">
                <div class="hero-pattern-circle"></div>

                <div class="hero-img-frame">
                    <img src="{{ asset('images/babak.jpg') }}"
                        onerror="this.style.background='linear-gradient(135deg,#cfa86e,#a67c52)';this.style.display='block'"
                        alt="بابک ابدالی" class="hero-img">
                    <div class="img-label">
                        <strong>بابک ابدالی</strong><br>
                        <small style="color:var(--gold-dark);font-size:0.75rem;">وکیل پایه یک دادگستری</small>
                    </div>
                </div>

                <div class="hero-img-frame" style="margin-top: 40px;">
                    <img src="{{ asset('images/zahra.jpg') }}"
                        onerror="this.style.background='linear-gradient(135deg,#c4a882,#967050)';this.style.display='block'"
                        alt="زهرا جوشقانی" class="hero-img">
                    <div class="img-label">
                        <strong>زهرا جوشقانی</strong><br>
                        <small style="color:var(--gold-dark);font-size:0.75rem;">وکیل پایه یک دادگستری</small>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
    STATS BAR
    ═══════════════════════════════════════════════════════════ --}}
    <div class="stats-wrapper">
        <div class="stats-card">
            <div class="stat-item">
                <span class="stat-number">+۴۸</span>
                <span class="stat-label">سال تجربه مشترک</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">+۲۰۰۰</span>
                <span class="stat-label">پرونده موفق</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">۹۸٪</span>
                <span class="stat-label">رضایت موکلین</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">۲</span>
                <span class="stat-label">وکیل پایه یک</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
    SERVICES
    ═══════════════════════════════════════════════════════════ --}}
    <section class="services">
        <div class="section-header">
            <h2>خدمات تخصصی ما</h2>
            <p>راهکارهای حقوقی دقیق برای چالش‌های شما</p>
        </div>
        <div class="services-grid">
            {{-- از متغیر $services که از HomeController ارسال شده استفاده می‌کنیم --}}
            @forelse($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="service-card">

                    {{-- لود کردن عکس از دیتابیس با قابلیت Fallback (اگر عکس نداشت یک عکس پیش‌فرض لود شود) --}}
                    <img src="{{ $service->image ? asset('storage/' . $service->image) : 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=70' }}"
                        alt="{{ $service->title }}" loading="lazy"
                        onerror="this.src='https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=70'">

                    <div class="service-content">
                        <div class="service-icon">
                            {{-- ایکون اختصاصی از دیتابیس --}}
                            <i class="{{ $service->icon ?? 'fas fa-gavel' }}"></i>
                        </div>
                        <h3 class="service-title">{{ $service->title }}</h3>

                        {{-- توضیحات کوتاه. اگر توضیحات کوتاه خالی بود، بخشی از توضیحات اصلی رو نشون می‌ده --}}
                        <p class="service-desc">
                            {{ $service->short_description ?? Str::limit(strip_tags($service->description), 70) }}
                        </p>
                    </div>
                </a>
            @empty
                {{-- در صورتی که هیچ خدماتی در دیتابیس ثبت نشده باشد --}}
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: #888;">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 15px; color: var(--gold-main);"></i>
                    <p>در حال حاضر خدمتی در سیستم ثبت نشده است.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
    CALCULATOR BANNER
    ═══════════════════════════════════════════════════════════ --}}
    <div class="calc-banner">
        <div class="calc-banner-inner">
            <div class="calc-banner-text">
                <h3><i class="fas fa-calculator" style="color:var(--gold-main);margin-left:10px;"></i>ماشین‌حساب‌های حقوقی
                    رایگان</h3>
                <p>بدون نیاز به وکیل، در چند ثانیه مهریه، دیه، هزینه دادرسی و خسارت تأخیر را محاسبه کنید.</p>
                <div class="calc-banner-chips">
                    <span class="calc-chip">مهریه به نرخ روز</span>
                    <span class="calc-chip">دیه سال ۱۴۰۴</span>
                    <span class="calc-chip">هزینه دادرسی</span>
                    <span class="calc-chip">خسارت تأخیر تأدیه</span>
                </div>
            </div>
            <a href="{{ route('calculators.index') }}" class="calc-banner-btn">
                <i class="fas fa-calculator"></i> ورود به ابزارها
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
    ARTICLES
    ═══════════════════════════════════════════════════════════ --}}
    <section class="articles">
        <div class="section-header">
            <h2>مقالات و دانستنی‌ها</h2>
            <p>آخرین مطالب حقوقی برای آگاهی بیشتر شما</p>
        </div>

        <div class="services-grid">
            @php
                $articles = [
                    [
                        'date' => '۱۴۰۴/۰۲/۱۲',
                        'cat' => 'حقوق خانواده',
                        'title' => 'شرایط جدید پرداخت مهریه در سال جاری',
                        'excerpt' => 'بررسی قوانین جدید و بخشنامه‌های صادره در خصوص نحوه مطالبه و پرداخت مهریه در دستگاه قضایی...',
                        'img' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=600&q=70',
                        'slug' => 'mahrieh-1404',
                    ],
                    [
                        'date' => '۱۴۰۴/۰۲/۰۵',
                        'cat' => 'دعاوی ملکی',
                        'title' => 'راهنمای جامع خرید ملک و تنظیم مبایعه‌نامه',
                        'excerpt' => 'نکات کلیدی و حیاتی که پیش از امضای هرگونه قرارداد ملکی باید بدانید تا متضرر نشوید...',
                        'img' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=600&q=70',
                        'slug' => 'real-estate-guide',
                    ],
                    [
                        'date' => '۱۴۰۴/۰۱/۲۸',
                        'cat' => 'امور چک',
                        'title' => 'قانون جدید چک‌های صیادی و روش‌های رفع سوءاثر',
                        'excerpt' => 'همه آنچه باید درباره چک‌های بنفش و نحوه پیگیری قضایی آن‌ها در سیستم بانکی بدانید...',
                        'img' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=70',
                        'slug' => 'sayyadi-check',
                    ],
                ];
            @endphp

            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article['slug']) }}" class="article-card">
                    <div class="article-img-box">
                        <img src="{{ $article['img'] }}" class="article-img" alt="{{ $article['title'] }}" loading="lazy">
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <i class="far fa-calendar-alt"></i> {{ $article['date'] }}
                            <span style="margin:0 5px;color:#ddd;">|</span>
                            <span>{{ $article['cat'] }}</span>
                        </div>
                        <h3 class="article-title">{{ $article['title'] }}</h3>
                        <p class="article-excerpt">{{ $article['excerpt'] }}</p>
                        <span class="read-more">
                            ادامه مطلب <i class="fas fa-arrow-left"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:40px;">
            <a href="{{ route('articles.index') }}" class="btn btn-outline">
                مشاهده همه مقالات <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
    CONTACT
    ═══════════════════════════════════════════════════════════ --}}
    <section class="contact-section">
        <div class="contact-container">

            {{-- اطلاعات تماس --}}
            <div class="contact-info-box">
                <h3 class="info-title">اطلاعات تماس</h3>

                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="info-text">
                        <h4>آدرس دفتر</h4>
                        <p>اصفهان، میدان جمهوری، جنب کلانتری ۱۲،<br>ساختمان جمهوری، طبقه اول، واحد ۴</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div class="info-text">
                        <h4>شماره‌های تماس</h4>
                        <p dir="ltr" style="text-align:right;">۰۹۱۳۱۱۴۶۸۸۸ — بابک ابدالی</p>
                        <p dir="ltr" style="text-align:right;">۰۹۱۳۲۸۸۸۸۵۹ — زهرا جوشقانی</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div class="info-text">
                        <h4>ساعات کاری</h4>
                        <p>شنبه تا چهارشنبه: ۱۷ الی ۲۱</p>
                        <p>پنج‌شنبه‌ها: با وقت قبلی</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div class="info-text">
                        <h4>ایمیل</h4>
                        <p>info@abdali-jooshghani.ir</p>
                    </div>
                </div>

                {{-- دکمه واتساپ --}}
                <a href="https://wa.me/989131146888" target="_blank"
                    style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#fff;padding:12px 20px;border-radius:8px;font-weight:600;font-size:0.88rem;margin-top:10px;transition:0.3s;"
                    onmouseover="this.style.background='rgba(37,211,102,0.2)';this.style.borderColor='rgba(37,211,102,0.4)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.borderColor='rgba(255,255,255,0.15)'">
                    <i class="fab fa-whatsapp" style="font-size:1.2rem;color:#25d366;"></i>
                    ارسال پیام در واتساپ
                </a>
            </div>

            {{-- فرم تماس --}}
            <div class="contact-form-box">
                <h3 class="form-title">درخواست مشاوره رایگان</h3>
                <p class="form-desc">فرم زیر را پر کنید تا در اسرع وقت با شما تماس بگیریم.</p>

                <form action="{{ route('contact') }}" method="POST">
                    @csrf
                    <div class="form-group-c">
                        <input type="text" name="name" class="form-input-c" placeholder="نام و نام خانوادگی" required>
                    </div>
                    <div class="form-group-c">
                        <input type="tel" name="phone" class="form-input-c" placeholder="شماره تماس" required
                            style="direction:ltr;text-align:right;">
                    </div>
                    <div class="form-group-c">
                        <input type="text" name="subject" class="form-input-c" placeholder="موضوع پرونده">
                    </div>
                    <div class="form-group-c">
                        <textarea name="message" class="form-input-c" placeholder="شرح مختصر مشکل حقوقی..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                        ارسال درخواست <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        </div>
    </section>

@endsection