@extends('layouts.public')

@section('title', 'دفتر وکالت ابدالی و جوشقانی | تجربه و اصالت')

@push('styles')
    <style>
        :root {
            --gold-main: #d4af37;
            --gold-dark: #aa8222;
            --gold-light: #f9f1d8;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --text-heading: #0f172a;
            --text-body: #64748b;
            --bg-section: #f8fafc;
            --shadow-soft: 0 20px 40px -15px rgba(212, 175, 55, 0.15);
            --shadow-card: 0 20px 40px -5px rgba(15, 23, 42, 0.05);
            --radius-md: 16px;
            --radius-lg: 30px;
            --radius-arch: 140px 140px 16px 16px;
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 12px; padding: 14px 36px; font-size: 1rem; font-weight: 800;
            border-radius: var(--radius-md); cursor: pointer; transition: var(--transition);
            border: none; font-family: 'Vazirmatn', sans-serif; position: relative; overflow: hidden;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--gold-main) 0%, var(--gold-dark) 100%);
            color: #fff; box-shadow: 0 10px 25px -5px rgba(212, 175, 55, 0.5); text-decoration: none;
        }
        .btn-primary:hover { 
            transform: translateY(-3px); box-shadow: 0 15px 35px -5px rgba(212, 175, 55, 0.7); color: #fff; 
        }
        .btn-outline {
            background: rgba(255,255,255,0.9); color: var(--text-heading);
            border: 2px solid transparent; text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); backdrop-filter: blur(10px);
        }
        .btn-outline:hover { 
            border-color: var(--gold-main); color: var(--gold-dark); 
            transform: translateY(-2px); box-shadow: var(--shadow-soft);
        }

        /* ─── SECTION TITLES ─── */
        .section-header { text-align: center; margin-bottom: 60px; padding: 0 20px; }
        .section-header h2 { font-size: clamp(1.8rem, 4vw, 2.5rem); color: var(--text-heading); font-weight: 900; margin-bottom: 15px; letter-spacing: -0.5px;}
        .section-header p { color: var(--text-body); font-size: clamp(0.95rem, 2vw, 1.1rem); max-width: 600px; margin: 0 auto; line-height: 1.8;}

        /* ─── HERO ─── */
        .hero {
            position: relative; padding: clamp(60px, 10vw, 120px) 0 clamp(120px, 15vw, 180px); overflow: hidden;
            background: linear-gradient(to left, rgba(253,251,247,0.85) 0%, rgba(253,251,247,1) 70%),
                url("{{ asset('assets/images/hero.png') }}");
            background-size: cover; background-position: center; background-attachment: fixed;
        }
        .hero-container {
            max-width: 1300px; margin: 0 auto; padding: 0 20px;
            display: grid; grid-template-columns: 1fr 1fr; align-items: center; gap: 40px; position: relative; z-index: 2;
        }
        .hero-content h2 {
            font-size: clamp(0.9rem, 2vw, 1.05rem); color: var(--gold-dark); font-weight: 800;
            margin-bottom: 20px; display: flex; align-items: center; gap: 12px;
        }
        .hero-content h2::before { content: ''; width: 40px; height: 3px; background: var(--gold-main); border-radius: 5px; }
        .hero-content .main-title {
            font-size: clamp(2rem, 5vw, 3.8rem); font-weight: 900;
            color: var(--text-heading); line-height: 1.25; margin-bottom: 25px; letter-spacing: -1px;
        }
        .hero-content .main-title span { 
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; 
        }
        .hero-content p { font-size: clamp(0.95rem, 2vw, 1.1rem); margin-bottom: 40px; max-width: 540px; text-align: justify; color: var(--text-body); line-height: 2; }
        .hero-actions { display: flex; gap: 15px; flex-wrap: wrap; }
        
        .hero-visual { position: relative; display: flex; justify-content: center; align-items: center; height: 100%; min-height: 450px;}
        .hero-pattern-circle {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: clamp(300px, 50vw, 600px); height: clamp(300px, 50vw, 600px); border: 2px dashed rgba(212, 175, 55, 0.2);
            border-radius: 50%; z-index: -1; animation: spin 60s linear infinite;
        }
        @keyframes spin { 100% { transform: translate(-50%, -50%) rotate(360deg); } }
        
        .hero-img-frame {
            position: absolute; width: clamp(160px, 20vw, 250px); height: clamp(240px, 30vw, 380px);
            border-radius: var(--radius-arch); overflow: hidden;
            border: 6px solid rgba(255,255,255,0.8); backdrop-filter: blur(10px);
            box-shadow: var(--shadow-soft); transition: var(--transition); background-color: #f5f0ea;
        }
        /* چینش اورلپ (روی هم افتاده) عکس‌ها برای زیبایی بیشتر */
        .hero-visual > .hero-img-frame:nth-child(2) { right: 10%; top: 0; z-index: 2;}
        .hero-visual > .hero-img-frame:nth-child(3) { left: 10%; bottom: 0; z-index: 3; transform: translateY(30px); }
        
        .hero-img-frame:hover { transform: translateY(-10px) scale(1.03); border-color: #fff; z-index: 10 !important; box-shadow: 0 30px 60px rgba(0,0,0,0.15); }
        .hero-img { width: 100%; height: 100%; object-fit: cover; filter: contrast(1.05) saturate(1.1); transition: var(--transition); }
        .hero-img-frame:hover .hero-img { transform: scale(1.05); }
        
        .img-label {
            position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%) translateY(120%);
            width: 85%; background: rgba(255,255,255,0.95); padding: 10px; text-align: center;
            font-weight: 800; color: var(--text-heading); border-radius: 12px;
            transition: var(--transition); box-shadow: 0 10px 20px rgba(0,0,0,0.1); backdrop-filter: blur(5px);
        }
        .img-label strong { font-size: clamp(0.8rem, 1.5vw, 1rem); }
        .img-label small { font-size: clamp(0.65rem, 1vw, 0.75rem); color: var(--gold-dark); }
        .hero-img-frame:hover .img-label { transform: translateX(-50%) translateY(0); }

        /* ─── STATS ─── */
        .stats-wrapper { max-width: 1150px; margin: -80px auto 0; position: relative; z-index: 10; padding: 0 20px; }
        .stats-card {
            background: rgba(255,255,255,0.98); backdrop-filter: blur(20px);
            border-radius: var(--radius-lg); padding: clamp(25px, 4vw, 45px);
            box-shadow: var(--shadow-card); display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 20px; position: relative; border: 1px solid rgba(212, 175, 55, 0.15);
        }
        .stats-card::before {
            content: ''; position: absolute; top: -1px; left: 10%; right: 10%; height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold-main), transparent); border-radius: 10px;
        }
        .stat-item { text-align: center; position: relative; }
        .stat-item:not(:last-child)::after {
            content: ''; position: absolute; left: 0; top: 15%; height: 70%;
            width: 1px; background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.08), transparent);
        }
        .stat-number { display: block; font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: var(--navy); line-height: 1; margin-bottom: 5px; }
        .stat-label { font-size: clamp(0.8rem, 1.5vw, 0.95rem); color: var(--text-body); font-weight: 600; }

        /* ─── FEATURES (چرا ما؟) - NEW ─── */
        .features { padding: 100px 20px 40px; max-width: 1200px; margin: 0 auto; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .feature-card {
            background: #fff; padding: 40px 30px; border-radius: var(--radius-lg);
            text-align: center; border: 1px solid rgba(0,0,0,0.03); transition: var(--transition);
            box-shadow: 0 10px 30px rgba(15,23,42,0.03);
        }
        .feature-card:hover { transform: translateY(-10px); box-shadow: var(--shadow-card); border-color: rgba(212,175,55,0.2); }
        .feature-icon {
            width: 70px; height: 70px; margin: 0 auto 20px; background: var(--bg-section);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: var(--gold-main); transition: var(--transition);
        }
        .feature-card:hover .feature-icon { background: var(--gold-main); color: #fff; transform: rotateY(180deg); }
        .feature-card h3 { font-size: 1.2rem; color: var(--navy); font-weight: 800; margin-bottom: 12px; }
        .feature-card p { font-size: 0.95rem; color: var(--text-body); line-height: 1.7; }

        /* ─── SERVICES ─── */
        .services { padding: 60px 20px 100px; max-width: 1200px; margin: 0 auto; }
        .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .service-card { height: clamp(350px, 40vw, 420px); border-radius: var(--radius-lg); overflow: hidden; position: relative; cursor: pointer; box-shadow: var(--shadow-card); }
        .service-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1); opacity: 0.9; }
        .service-card:hover img { transform: scale(1.08); opacity: 1; }
        .service-content {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.4) 60%, transparent 100%);
            display: flex; flex-direction: column; justify-content: flex-end; align-items: center;
            padding: 30px; z-index: 2; transition: var(--transition);
        }
        .service-icon {
            width: 60px; height: 60px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.2); transition: var(--transition);
        }
        .service-icon i { color: var(--gold-light); font-size: 1.5rem; transition: var(--transition); }
        .service-card:hover .service-icon { background: var(--gold-main); border-color: var(--gold-main); transform: translateY(-10px) scale(1.1); box-shadow: 0 10px 20px rgba(212,175,55,0.3); }
        .service-card:hover .service-icon i { color: #fff; }
        .service-title { color: #fff; font-size: clamp(1.2rem, 2vw, 1.4rem); font-weight: 800; margin-bottom: 10px; transition: var(--transition); text-align: center;}
        .service-card:hover .service-title { transform: translateY(-5px); color: var(--gold-light); }
        .service-desc { color: rgba(255,255,255,0.8); font-size: 0.9rem; text-align: center; opacity: 0; transform: translateY(20px); transition: var(--transition); max-height: 0; overflow: hidden; line-height: 1.7; }
        .service-card:hover .service-desc { opacity: 1; transform: translateY(0); max-height: 100px; margin-top: 5px; }

        /* ─── CALCULATOR BANNER ─── */
        .calc-banner { max-width: 1200px; margin: 0 auto 100px; padding: 0 20px; }
        .calc-banner-inner {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            border-radius: var(--radius-lg); padding: clamp(30px, 5vw, 60px) clamp(20px, 5vw, 70px);
            display: flex; align-items: center; justify-content: space-between;
            gap: 30px; position: relative; overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); border: 1px solid rgba(255,255,255,0.05);
        }
        .calc-banner-inner::before { content: '§'; position: absolute; left: 20px; top: 50%; transform: translateY(-50%); font-size: clamp(10rem, 20vw, 16rem); color: rgba(212,175,55,0.04); font-family: serif; line-height: 1; pointer-events: none; }
        .calc-banner-text { position: relative; z-index: 1; }
        .calc-banner-text h3 { color: #fff; font-size: clamp(1.3rem, 3vw, 1.6rem); font-weight: 900; margin-bottom: 15px; }
        .calc-banner-text p { color: rgba(255,255,255,0.7); font-size: clamp(0.9rem, 1.5vw, 1rem); line-height: 1.9; max-width: 550px; }
        .calc-banner-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 20px; }
        .calc-chip { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--gold-light); padding: 6px 14px; border-radius: 30px; font-size: 0.8rem; font-weight: 600; backdrop-filter: blur(5px);}
        .calc-banner-btn {
            background: #fff; color: var(--navy); padding: 16px 32px; border-radius: var(--radius-md);
            font-weight: 900; font-size: 1rem; display: flex; align-items: center; gap: 10px;
            white-space: nowrap; transition: var(--transition); text-decoration: none;
            position: relative; z-index: 1; flex-shrink: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .calc-banner-btn:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); color: var(--gold-dark); }

        /* ─── FAQ (سوالات متداول) - NEW ─── */
        .faq-section { padding: 40px 20px 80px; max-width: 900px; margin: 0 auto; }
        .faq-item { background: #fff; border-radius: var(--radius-md); margin-bottom: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.03); overflow: hidden; transition: var(--transition);}
        .faq-item:hover { border-color: rgba(212,175,55,0.3); }
        .faq-item details { padding: 0; }
        .faq-item summary { 
            padding: 20px 25px; font-weight: 800; color: var(--navy); font-size: 1.05rem; 
            cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center;
        }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary::after { content: '\f067'; font-family: 'Font Awesome 6 Free'; font-weight: 900; color: var(--gold-main); transition: var(--transition); }
        .faq-item details[open] summary::after { content: '\f068'; transform: rotate(180deg); }
        .faq-item details[open] summary { border-bottom: 1px dashed rgba(0,0,0,0.05); }
        .faq-answer { padding: 20px 25px; color: var(--text-body); line-height: 1.8; font-size: 0.95rem; background: var(--bg-section); }

        /* ─── ARTICLES ─── */
        .articles { padding: 50px 20px 100px; max-width: 1200px; margin: 0 auto; }
        .article-card {
            background: #fff; border-radius: var(--radius-lg); overflow: hidden;
            box-shadow: var(--shadow-card); transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02); display: flex; flex-direction: column;
            text-decoration: none; color: inherit; height: 100%;
        }
        .article-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.1); }
        .article-img-box { height: 220px; position: relative; overflow: hidden; }
        .article-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
        .article-card:hover .article-img { transform: scale(1.05); }
        .article-content { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; background: #fff; position: relative; z-index: 2; }
        .article-meta { font-size: 0.8rem; color: var(--gold-dark); margin-bottom: 15px; display: flex; align-items: center; gap: 8px; font-weight: 700; background: var(--gold-light); padding: 5px 12px; border-radius: 20px; width: fit-content;}
        .article-title { font-size: 1.15rem; font-weight: 900; color: var(--text-heading); margin-bottom: 12px; line-height: 1.6; transition: 0.3s; }
        .article-card:hover .article-title { color: var(--navy); }
        .article-excerpt { font-size: 0.9rem; color: var(--text-body); margin-bottom: 20px; line-height: 1.8; }
        .read-more { margin-top: auto; color: var(--navy); font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
        .article-card:hover .read-more { color: var(--gold-main); gap: 12px; }

        /* ─── CONTACT ─── */
        .contact-section { padding: 100px 20px; background-color: #fff; position: relative; }
        .contact-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1.5fr; gap: clamp(30px, 5vw, 50px); align-items: stretch; }
        .contact-info-box { background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%); color: #fff; padding: clamp(30px, 5vw, 50px); border-radius: var(--radius-lg); position: relative; overflow: hidden; box-shadow: var(--shadow-card); }
        .contact-info-box::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, var(--gold-main), var(--gold-light)); }
        .info-title { font-size: clamp(1.5rem, 3vw, 2rem); margin-bottom: 40px; color: var(--gold-main); font-weight: 900;}
        .info-item { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 30px; }
        .info-item i { color: var(--gold-light); font-size: 1.4rem; margin-top: 5px; background: rgba(255,255,255,0.05); padding: 12px; border-radius: 50%; min-width: 50px; text-align: center;}
        .info-text h4 { font-size: 1.1rem; margin-bottom: 5px; color: #fff; font-weight: 800;}
        .info-text p { color: #cbd5e1; font-size: 0.95rem; line-height: 1.8; }
        
        .whatsapp-btn {
            display:inline-flex; align-items:center; justify-content:center; gap:10px; background: rgba(37, 211, 102, 0.1); 
            border:1px solid rgba(37, 211, 102, 0.3); color:#fff; padding:14px 24px; border-radius:var(--radius-md); 
            font-weight:700; font-size:0.95rem; margin-top:15px; transition:var(--transition); text-decoration:none; width: 100%;
        }
        .whatsapp-btn:hover { background: #25d366; color: #fff; box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3); transform: translateY(-2px);}

        /* RESPONSIVE MEDIA QUERIES */
        @media (max-width: 1024px) {
            .hero-container { grid-template-columns: 1fr; text-align: center; }
            .hero-content h2 { justify-content: center; }
            .hero-content h2::before { display: none; }
            .hero-actions { justify-content: center; }
            .hero-content p { margin: 0 auto 40px; }
            
            .hero-visual { height: 350px; margin-top: 20px; }
            .hero-img-frame { width: 45%; height: 300px; position: static; transform: none !important;}
            /* کنار هم قرار دادن عکس‌ها در تبلت */
            .hero-visual > .hero-img-frame:nth-child(2),
            .hero-visual > .hero-img-frame:nth-child(3) { position: relative; margin: 0 10px; }
            
            .calc-banner-inner { flex-direction: column; text-align: center; }
            .calc-banner-chips { justify-content: center; }
            .contact-container { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .stats-card { grid-template-columns: repeat(2, 1fr); gap: 20px; }
            .stat-item:not(:last-child)::after { display: none; } /* حذف خطوط عمودی در موبایل */
            .stat-item { padding: 15px 0; background: var(--bg-section); border-radius: 12px; }
            
            .hero-visual { flex-direction: column; height: auto; gap: 20px; }
            .hero-img-frame { width: 80%; height: 320px; margin: 0 !important; }
            .hero-pattern-circle { width: 90vw; height: 90vw; }
        }
        
        @media (max-width: 480px) {
            .btn { width: 100%; }
            .hero-actions { flex-direction: column; width: 100%; }
            .stats-card { grid-template-columns: 1fr; } /* تک ستونه در گوشی‌های خیلی کوچک */
        }
    </style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h2>تجربه مشترک، نتیجه تضمینی</h2>
                <h1 class="main-title">
                    دفاع از حق شما<br>
                    <span>تخصص ماست</span>
                </h1>
                <p>موسسه حقوقی بابک ابدالی و زهرا جوشقانی، با بیش از دو دهه سابقه درخشان در مراجع قضایی اصفهان. ما مسیرهای پیچیده قانونی را برای شما با دقت و شفافیت هموار می‌کنیم.</p>
                <div class="hero-actions">
                    <a href="{{ route('reserve.index') }}" class="btn btn-primary">
                        <i class="fas fa-phone-alt"></i> درخواست مشاوره فوری
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline">مشاهده سوابق کاری</a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-pattern-circle"></div>
                <div class="hero-img-frame">
                    <img src="{{ asset('assets/images/babak.png') }}"
                         onerror="this.style.background='linear-gradient(135deg,#d4af37,#aa8222)'"
                         alt="بابک ابدالی" class="hero-img">
                    <div class="img-label">
                        <strong>بابک ابدالی</strong><br>
                        <small>وکیل پایه یک دادگستری</small>
                    </div>
                </div>
                <div class="hero-img-frame">
                    <img src="{{ asset('assets/images/zahra.png') }}"
                         onerror="this.style.background='linear-gradient(135deg,#e6cfa3,#9e7f41)'"
                         alt="زهرا جوشقانی" class="hero-img">
                    <div class="img-label">
                        <strong>زهرا جوشقانی</strong><br>
                        <small>وکیل پایه یک دادگستری</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS --}}
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

    {{-- FEATURES (NEW SECTION) --}}
    <section class="features">
        <div class="section-header">
            <h2>چرا ما را انتخاب کنید؟</h2>
            <p>وکالت یک تعهد است. ما با رویکردی متفاوت، آرامش خاطر را در مسیرهای پرالتهاب قانونی به شما هدیه می‌دهیم.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-balance-scale-right"></i></div>
                <h3>تخصص و تجربه بالا</h3>
                <p>سال‌ها حضور موفق در محاکم قضایی و پیگیری پرونده‌های پیچیده، پشتوانه ما در دفاع از حقوق شماست.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                <h3>شفافیت و صداقت</h3>
                <p>ما پیش از شروع هر پرونده، مسیر حقوقی، هزینه‌ها و درصد موفقیت را با شفافیت کامل برای شما روشن می‌کنیم.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-comments"></i></div>
                <h3>پیگیری و پشتیبانی</h3>
                <p>از طریق پنل کاربری اختصاصی، در هر لحظه می‌توانید از آخرین وضعیت و روند پیشرفت پرونده خود مطلع شوید.</p>
            </div>
        </div>
    </section>

    {{-- SERVICES --}}
    <section class="services">
        <div class="section-header">
            <h2>حوزه‌های تخصصی وکالت</h2>
            <p>ارائه خدمات دقیق و تخصصی حقوقی، کیفری و خانواده متناسب با نیازهای شما</p>
        </div>
        <div class="services-grid">
            @forelse($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="service-card">
                    <img src="{{ $service->image ? asset('storage/'.$service->image) : 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=70' }}"
                         alt="{{ $service->title }}" loading="lazy"
                         onerror="this.src='https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=70'">
                    <div class="service-content">
                        <div class="service-icon">
                            <i class="{{ $service->icon ?? 'fas fa-gavel' }}"></i>
                        </div>
                        <h3 class="service-title">{{ $service->title }}</h3>
                        <p class="service-desc">{{ $service->short_description ?? Str::limit(strip_tags($service->description), 70) }}</p>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:50px;color:#888;background:#fff;border-radius:20px;">
                    <i class="fas fa-box-open" style="font-size:3rem;margin-bottom:15px;color:var(--gold-main);display:block;"></i>
                    <p>در حال حاضر خدمتی در سیستم ثبت نشده است.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- CALCULATOR BANNER --}}
    <div class="calc-banner">
        <div class="calc-banner-inner">
            <div class="calc-banner-text">
                <h3><i class="fas fa-calculator" style="color:var(--gold-main);margin-left:10px;"></i>محاسبات حقوقی در یک نگاه</h3>
                <p>بدون نیاز به مراجعه حضوری و صرف هزینه، محاسبات پیچیده قضایی را در چند ثانیه و کاملاً رایگان انجام دهید.</p>
                <div class="calc-banner-chips">
                    <span class="calc-chip">مهریه به نرخ روز</span>
                    <span class="calc-chip">محاسبه دیه</span>
                    <span class="calc-chip">هزینه دادرسی</span>
                    <span class="calc-chip">تأخیر تأدیه</span>
                </div>
            </div>
            <a href="{{ route('calculators.index') }}" class="calc-banner-btn">
                <i class="fas fa-arrow-right"></i> ورود به ابزارها
            </a>
        </div>
    </div>

    {{-- FAQ (NEW SECTION) --}}
    <section class="faq-section">
        <div class="section-header">
            <h2>سوالات متداول</h2>
            <p>پاسخ به پرسش‌هایی که ممکن است پیش از مراجعه به دفتر داشته باشید</p>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <details>
                    <summary>چگونه می‌توانم وقت مشاوره حضوری رزرو کنم؟</summary>
                    <div class="faq-answer">شما می‌توانید از طریق دکمه "درخواست مشاوره فوری" در بالای همین صفحه، یا تماس مستقیم با شماره‌های درج شده در بخش ارتباط با ما، وقت مشاوره حضوری یا تلفنی خود را تنظیم کنید.</div>
                </details>
            </div>
            <div class="faq-item">
                <details>
                    <summary>آیا امکان مشاهده وضعیت پرونده به صورت آنلاین وجود دارد؟</summary>
                    <div class="faq-answer">بله، پس از سپردن پرونده به ما، یک حساب کاربری اختصاصی برای شما ایجاد می‌شود که می‌توانید در هر ساعت از شبانه‌روز، مدارک، لوایح و آخرین وضعیت پیشرفت پرونده خود را در پنل کاربری مشاهده کنید.</div>
                </details>
            </div>
            <div class="faq-item">
                <details>
                    <summary>حق‌الوکاله چگونه محاسبه و پرداخت می‌شود؟</summary>
                    <div class="faq-answer">حق‌الوکاله بر اساس نوع، پیچیدگی و زمان‌بر بودن پرونده و طبق تعرفه‌های قانونی یا توافق طرفین تعیین می‌شود. امکان پرداخت به صورت اقساط نیز در سیستم مالی ما برای رفاه حال موکلین فراهم است.</div>
                </details>
            </div>
        </div>
    </section>

    {{-- ARTICLES --}}
    <section class="articles" style="background: var(--bg-section); padding-top: 80px; border-radius: 40px 40px 0 0;">
        <div class="section-header">
            <h2>تازه‌های حقوقی</h2>
            <p>افزایش آگاهی حقوقی، اولین قدم برای جلوگیری از مشکلات قانونی است</p>
        </div>

        <div class="services-grid">
            @forelse($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="article-card">
                    <div class="article-img-box">
                        @if($article->featured_image)
                            <img src="{{ $article->image_url }}"
                                 class="article-img" alt="{{ $article->title }}" loading="lazy">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-newspaper" style="font-size:3rem;color:rgba(212,175,55,0.3);"></i>
                            </div>
                        @endif
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            @if($article->published_at)
                                <i class="far fa-calendar-alt"></i>
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($article->published_at)->format('Y/m/d') }}
                            @endif
                        </div>
                        <h3 class="article-title">{{ $article->title }}</h3>
                        @if($article->excerpt)
                            <p class="article-excerpt">{{ Str::limit($article->excerpt, 90) }}</p>
                        @endif
                        <span class="read-more">مطالعه کامل <i class="fas fa-arrow-left"></i></span>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:40px;">
                    <p>هنوز مقاله‌ای منتشر نشده است.</p>
                </div>
            @endforelse
        </div>

        @if($articles->isNotEmpty())
        <div style="text-align:center;margin-top:50px;">
            <a href="{{ route('articles.index') }}" class="btn btn-outline" style="background:#fff;">
                آرشیو کامل مقالات <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        @endif
    </section>

    {{-- CONTACT --}}
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-info-box">
                <h3 class="info-title">راه‌های ارتباطی</h3>
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
                        <p>پنج‌شنبه‌ها: با تعیین وقت قبلی</p>
                    </div>
                </div>
                <a href="https://wa.me/989131146888" target="_blank" class="whatsapp-btn">
                    <i class="fab fa-whatsapp" style="font-size:1.3rem;"></i>
                    شروع چت در واتساپ
                </a>
            </div>

            <div class="contact-form-box">
                <h3 class="form-title" style="font-size: 1.8rem; color: var(--text-heading); margin-bottom: 10px; font-weight: 900;">درخواست مشاوره حقوقی</h3>
                <p style="margin-bottom: 30px; color: var(--text-body);">لطفاً مشخصات خود را وارد کنید تا در اولین فرصت با شما تماس بگیریم.</p>

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    
                    <div class="form-group-c">
                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" placeholder="نام و نام خانوادگی" required value="{{ old('name') }}">
                        </div>
                        @error('name')
                            <div class="form-error" style="color: #ef4444; font-size: 0.85rem; margin-top: 8px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group-c">
                        <div class="input-box">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="tel" name="phone" placeholder="شماره تماس (مثال: 0912...)" required style="direction:ltr;text-align:right;" value="{{ old('phone') }}">
                        </div>
                        @error('phone')
                            <div class="form-error" style="color: #ef4444; font-size: 0.85rem; margin-top: 8px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group-c">
                        <div class="input-box">
                            <i class="fas fa-gavel"></i>
                            <input type="text" name="service" placeholder="موضوع پرونده (اختیاری)" value="{{ old('service') }}">
                        </div>
                    </div>

                    <div class="form-group-c">
                        <div class="input-box textarea-box">
                            <i class="fas fa-envelope-open-text"></i>
                            <textarea name="message" placeholder="شرح مختصر مشکل حقوقی...">{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:10px;">
                        ارسال پیام <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

@endsection