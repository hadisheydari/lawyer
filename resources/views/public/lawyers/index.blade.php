@extends('layouts.public')
@section('title', 'درباره وکلا | ابدالی و جوشقانی')

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
            --shadow-soft: 0 20px 40px -15px rgba(212, 175, 55, 0.15);
            --shadow-card: 0 20px 40px -5px rgba(15, 23, 42, 0.05);
            --radius-md: 16px;
            --radius-lg: 30px;
            --radius-arch: 140px 140px 16px 16px;
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .lawyers-intro {
            max-width: 1250px;
            margin: 0 auto;
            padding: clamp(50px, 8vw, 100px) 20px;
        }

        /* ─── کارت وکیل ──────────────────────────────────────────────── */
        .lawyer-card {
            display: grid;
            grid-template-columns: clamp(300px, 30vw, 400px) 1fr;
            gap: clamp(30px, 5vw, 60px);
            align-items: center;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(212, 175, 55, 0.15);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            margin-bottom: 60px;
            transition: var(--transition);
            padding: 20px;
        }

        .lawyer-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px -10px rgba(15, 23, 42, 0.1);
            border-color: rgba(212, 175, 55, 0.4);
        }

        .lawyer-card.reverse {
            grid-template-columns: 1fr clamp(300px, 30vw, 400px);
        }

        .lawyer-card.reverse .lawyer-img-wrap {
            order: 2;
        }

        .lawyer-card.reverse .lawyer-bio {
            order: 1;
        }

        /* کادر عکس مشابه صفحه اصلی */
        .lawyer-img-wrap {
            position: relative;
            height: clamp(400px, 45vw, 540px);
            border-radius: var(--radius-arch);
            overflow: hidden;
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            border: 6px solid #f8fafc;
            box-shadow: var(--shadow-soft);
        }

        .lawyer-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
            filter: contrast(1.05) saturate(1.1);
            transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .lawyer-card:hover .lawyer-img-wrap img {
            opacity: 1;
            transform: scale(1.05);
        }

        .lawyer-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
            font-weight: 900;
            color: rgba(212, 175, 55, 0.3);
        }

        .lawyer-badge {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 85%;
            background: rgba(255, 255, 255, 0.95);
            color: var(--text-heading);
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-top: 2px solid var(--gold-main);
        }
        
        .lawyer-badge i {
            color: var(--gold-main);
            font-size: 1.1rem;
        }

        .lawyer-bio {
            padding: 30px 40px 30px 10px;
        }

        .lawyer-card.reverse .lawyer-bio {
            padding: 30px 10px 30px 40px;
        }

        .lawyer-tag {
            display: inline-block;
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold-dark);
            font-size: 0.8rem;
            font-weight: 800;
            padding: 6px 16px;
            border-radius: 30px;
            margin-bottom: 15px;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .lawyer-bio h2 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 900;
            color: var(--text-heading);
            margin-bottom: 10px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .lawyer-bio .title-line {
            font-size: 0.95rem;
            color: var(--text-body);
            font-weight: 600;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lawyer-bio .title-line i {
            color: var(--gold-main);
        }

        .lawyer-bio p {
            color: var(--text-body);
            font-size: 1rem;
            line-height: 1.9;
            text-align: justify;
            margin-bottom: 35px;
        }

        /* آمار وکیل */
        .lawyer-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 35px;
        }

        .ls-item {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px 15px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .lawyer-card:hover .ls-item {
            border-color: rgba(212, 175, 55, 0.2);
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        }

        .ls-num {
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 900;
            color: var(--navy);
            display: block;
            margin-bottom: 5px;
        }

        .ls-label {
            font-size: 0.8rem;
            color: var(--text-body);
            font-weight: 600;
            display: block;
        }

        /* تخصص‌ها */
        .expertise-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 35px;
        }

        .exp-tag {
            background: #fff;
            color: var(--navy);
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            transition: var(--transition);
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }

        .exp-tag:hover {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-contact-lawyer {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            color: #fff;
            padding: 14px 30px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.95rem;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.4);
            transition: var(--transition);
            text-decoration: none;
            margin-left: 12px;
            margin-bottom: 12px;
            border: none;
        }

        .btn-contact-lawyer:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.6);
            color: #fff;
        }

        /* ─── Bar شهادت‌نامه‌ها ─────────────────────────────────────── */
        .credentials-bar {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            padding: 80px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .credentials-bar::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--gold-main), var(--gold-light));
        }

        .credentials-bar h3 {
            color: #fff;
            font-size: clamp(1.5rem, 3vw, 2rem);
            margin-bottom: 50px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .credentials-bar h3 i {
            color: var(--gold-main);
        }

        .cred-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        .cred-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 35px 20px;
            color: #fff;
            transition: var(--transition);
        }

        .cred-item:hover {
            background: rgba(212, 175, 55, 0.08);
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateY(-5px);
        }

        .cred-item i {
            font-size: 2.2rem;
            color: var(--gold-light);
            margin-bottom: 20px;
            display: block;
            transition: var(--transition);
        }
        
        .cred-item:hover i {
            color: var(--gold-main);
            transform: scale(1.1);
        }

        .cred-item h4 {
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .cred-item p {
            font-size: 0.85rem;
            color: #cbd5e1;
            line-height: 1.6;
        }

        /* ─── CTA پایین صفحه ──────────────────────────────────────── */
        .lawyers-cta {
            max-width: 800px;
            margin: 100px auto;
            padding: 0 20px;
            text-align: center;
        }

        .lawyers-cta h2 {
            font-size: clamp(1.8rem, 3vw, 2.2rem);
            color: var(--text-heading);
            font-weight: 900;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .lawyers-cta p {
            color: var(--text-body);
            font-size: 1.05rem;
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .cta-btns {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* empty state */
        .no-lawyers {
            text-align: center;
            padding: 100px 20px;
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            border: 1px solid #e2e8f0;
        }
        .no-lawyers i { font-size: 4rem; color: rgba(212,175,55,0.3); margin-bottom: 20px; }
        .no-lawyers h3 { color: var(--text-heading); margin-bottom: 10px; font-size: 1.5rem; font-weight: 900; }
        .no-lawyers p { color: var(--text-body); font-size: 1rem;}

        @media (max-width: 960px) {
            .lawyer-card,
            .lawyer-card.reverse {
                grid-template-columns: 1fr;
                padding: 15px;
            }

            .lawyer-card.reverse .lawyer-img-wrap { order: 1; }
            .lawyer-card.reverse .lawyer-bio { order: 2; }

            .lawyer-img-wrap { height: 400px; }

            .lawyer-bio,
            .lawyer-card.reverse .lawyer-bio {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 480px) {
            .lawyer-stats { grid-template-columns: 1fr; gap: 10px; }
            .cta-btns a, .lawyer-bio .btn-contact-lawyer { width: 100%; margin-left: 0; }
        }
    </style>
@endpush

@section('content')

    {{-- Page Banner --}}
    <div class="page-banner" style="margin-right: 3%; margin-top: 3%; border-radius: 20px;">
        <div class="page-banner-inner">
            <h1><i class="fas fa-user-tie" style="color:var(--gold-main);margin-left:15px;"></i>درباره وکلا</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">صفحه اصلی</a>
                <i class="fas fa-chevron-left"></i>
                <span>درباره وکلا</span>
            </div>
        </div>
    </div>

    <div class="lawyers-intro">

        @forelse($lawyers as $index => $lawyer)

            <div class="lawyer-card {{ $index % 2 !== 0 ? 'reverse' : '' }}">

                {{-- عکس --}}
                <div class="lawyer-img-wrap">
                    @if($lawyer->image)
                        <img src="{{ 'assets/images/'.$lawyer->image }}" alt="{{ $lawyer->name }}">
                    @else
                        <div class="lawyer-img-placeholder">
                            {{ mb_substr($lawyer->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="lawyer-badge">
                        <i class="fas fa-certificate"></i>
                        وکیل پایه {{ $lawyer->license_grade }} دادگستری
                    </div>
                </div>

                {{-- بیو --}}
                <div class="lawyer-bio">
                    <span class="lawyer-tag">تخصص و تعهد</span>
                    <h2>{{ $lawyer->name }}</h2>
                    <div class="title-line">
                        <i class="fas fa-gavel"></i>
                        وکیل پایه {{ $lawyer->license_grade }} دادگستری — کانون وکلای اصفهان
                    </div>

                    <div class="lawyer-stats">
                        <div class="ls-item">
                            <span class="ls-num">{{ $lawyer->experience_years }}+</span>
                            <span class="ls-label">سال سابقه</span>
                        </div>
                        <div class="ls-item">
                            <span class="ls-num">پایه {{ $lawyer->license_grade }}</span>
                            <span class="ls-label">دادگستری</span>
                        </div>
                        <div class="ls-item">
                            <span class="ls-num">۹۸٪</span>
                            <span class="ls-label">رضایت موکلین</span>
                        </div>
                    </div>

                    @if($lawyer->bio)
                        <p>{{ Str::limit($lawyer->bio, 350) }}</p>
                    @endif

                    @if($lawyer->specializations && count($lawyer->specializations) > 0)
                        <div class="expertise-tags">
                            @foreach($lawyer->specializations as $spec)
                                <span class="exp-tag">{{ $spec }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div style="display:flex;flex-wrap:wrap;gap:0;">
                        <a href="{{ route('reserve.index', ['lawyer' => $lawyer->slug]) }}" class="btn-contact-lawyer">
                            <i class="fas fa-calendar-check"></i>
                            رزرو وقت مشاوره
                        </a>
                        <a href="{{ route('lawyers.show', $lawyer->slug) }}" class="btn-contact-lawyer" style="background:linear-gradient(135deg,var(--gold-main),var(--gold-dark));">
                            <i class="fas fa-user-circle"></i>
                            مشاهده پروفایل کامل
                        </a>
                    </div>
                </div>

            </div>

        @empty
            <div class="no-lawyers">
                <i class="fas fa-user-slash"></i>
                <h3>وکیلی ثبت نشده است</h3>
                <p>در حال حاضر اطلاعات وکلا در سیستم وارد نشده است.</p>
            </div>
        @endforelse

    </div>

    {{-- ─── Credentials Bar ─────────────────────────────────────── --}}
    @if($lawyers->isNotEmpty())
    <div class="credentials-bar">
        <h3><i class="fas fa-award"></i> افتخارات و سوابق حقوقی</h3>
        <div class="cred-grid">
            <div class="cred-item">
                <i class="fas fa-university"></i>
                <h4>عضویت در کانون وکلا</h4>
                <p>کانون وکلای دادگستری مرکز و اصفهان با پروانه معتبر</p>
            </div>
            <div class="cred-item">
                <i class="fas fa-graduation-cap"></i>
                <h4>تحصیلات عالیه</h4>
                <p>دانش‌آموخته حقوق از برترین دانشگاه‌های کشور</p>
            </div>
            <div class="cred-item">
                <i class="fas fa-trophy"></i>
                <h4>+{{ $lawyers->sum('experience_years') }} سال تجربه مشترک</h4>
                <p>حضور مستمر و موفق در مراجع قضایی سراسر کشور</p>
            </div>
            <div class="cred-item">
                <i class="fas fa-handshake"></i>
                <h4>پشتیبانی حقوقی مستمر</h4>
                <p>همراهی قدم‌به‌قدم با موکل تا رسیدن به نتیجه مطلوب</p>
            </div>
        </div>
    </div>

    {{-- ─── CTA ─────────────────────────────────────────────────── --}}
    <div class="lawyers-cta">
        <h2>آماده‌اید قدم اول را بردارید؟</h2>
        <p>با رزرو یک جلسه مشاوره اولیه، می‌توانید وضعیت دقیق پرونده خود را بررسی کرده و بهترین مسیر قانونی را انتخاب کنید.</p>
        <div class="cta-btns">
            <a href="{{ route('reserve.index') }}" class="btn btn-primary" style="margin: 0;">
                <i class="fas fa-calendar-check"></i> رزرو نوبت مشاوره
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline" style="margin: 0;">
                <i class="fas fa-phone"></i> تماس با دفتر
            </a>
        </div>
    </div>
    @endif

@endsection