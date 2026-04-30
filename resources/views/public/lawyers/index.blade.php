@extends('layouts.public')
@section('title', 'درباره وکلا | ابدالی و جوشقانی')

@push('styles')
    <style>
        .lawyers-intro {
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 20px;
        }

        /* ─── کارت وکیل ──────────────────────────────────────────────── */
        .lawyer-card {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 60px;
            align-items: start;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            margin-bottom: 50px;
            transition: transform 0.4s ease;
        }

        .lawyer-card:hover {
            transform: translateY(-5px);
        }

        .lawyer-card.reverse {
            grid-template-columns: 1fr 380px;
        }

        .lawyer-card.reverse .lawyer-img-wrap {
            order: 2;
        }

        .lawyer-card.reverse .lawyer-bio {
            order: 1;
        }

        .lawyer-img-wrap {
            position: relative;
            height: 520px;
            overflow: hidden;
            background: linear-gradient(135deg, #0a1c2e, #1e3a5f);
        }

        .lawyer-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.85;
            filter: contrast(1.05) saturate(0.85);
            transition: 0.5s;
        }

        .lawyer-card:hover .lawyer-img-wrap img {
            opacity: 1;
            transform: scale(1.03);
        }

        .lawyer-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
            font-weight: 900;
            color: rgba(197, 160, 89, 0.5);
        }

        .lawyer-badge {
            position: absolute;
            bottom: 25px;
            right: 25px;
            background: rgba(207, 168, 110, 0.95);
            color: #fff;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(5px);
        }

        .lawyer-bio {
            padding: 50px 50px 50px 20px;
        }

        .lawyer-card.reverse .lawyer-bio {
            padding: 50px 20px 50px 50px;
        }

        .lawyer-tag {
            display: inline-block;
            background: rgba(207, 168, 110, 0.12);
            color: var(--gold-dark);
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 30px;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .lawyer-bio h2 {
            font-size: 2rem;
            font-weight: 900;
            color: var(--text-heading);
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .lawyer-bio .title-line {
            font-size: 0.95rem;
            color: var(--gold-main);
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lawyer-bio p {
            color: var(--text-body);
            font-size: 0.95rem;
            line-height: 1.95;
            text-align: justify;
            margin-bottom: 30px;
        }

        /* آمار وکیل */
        .lawyer-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .ls-item {
            background: #fdfbf7;
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            border: 1px solid rgba(207, 168, 110, 0.15);
        }

        .ls-num {
            font-size: 1.7rem;
            font-weight: 900;
            color: var(--navy);
            display: block;
        }

        .ls-label {
            font-size: 0.75rem;
            color: var(--text-body);
            margin-top: 3px;
            display: block;
        }

        /* تخصص‌ها */
        .expertise-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 30px;
        }

        .exp-tag {
            background: #f0f4f8;
            color: var(--navy);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid rgba(16, 42, 67, 0.1);
            transition: 0.3s;
        }

        .exp-tag:hover {
            background: var(--navy);
            color: #fff;
        }

        .btn-contact-lawyer {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--navy), #1e3a5f);
            color: #fff;
            padding: 13px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.92rem;
            box-shadow: 0 8px 20px rgba(16, 42, 67, 0.2);
            transition: 0.3s;
            text-decoration: none;
            margin-left: 10px;
            margin-bottom: 10px;
        }

        .btn-contact-lawyer:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(16, 42, 67, 0.3);
            color: #fff;
        }

        /* ─── Bar شهادت‌نامه‌ها ─────────────────────────────────────── */
        .credentials-bar {
            background: var(--navy);
            padding: 50px 20px;
            text-align: center;
        }

        .credentials-bar h3 {
            color: var(--gold-main);
            font-size: 1.1rem;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .cred-grid {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .cred-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(207, 168, 110, 0.2);
            border-radius: 14px;
            padding: 25px 15px;
            color: #fff;
            transition: 0.3s;
        }

        .cred-item:hover {
            background: rgba(207, 168, 110, 0.12);
            border-color: var(--gold-main);
        }

        .cred-item i {
            font-size: 1.8rem;
            color: var(--gold-main);
            margin-bottom: 12px;
            display: block;
        }

        .cred-item h4 {
            font-size: 0.88rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .cred-item p {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* ─── CTA پایین صفحه ──────────────────────────────────────── */
        .lawyers-cta {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
            text-align: center;
        }

        .lawyers-cta h2 {
            font-size: 1.8rem;
            color: var(--text-heading);
            font-weight: 900;
            margin-bottom: 15px;
        }

        .lawyers-cta p {
            color: var(--text-body);
            margin-bottom: 30px;
        }

        .cta-btns {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 8px 20px rgba(207, 168, 110, 0.35);
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-gold:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(207, 168, 110, 0.5);
            color: #fff;
        }

        .btn-outline-navy {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 2px solid var(--navy);
            color: var(--navy);
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-outline-navy:hover {
            background: var(--navy);
            color: #fff;
        }

        /* empty state */
        .no-lawyers {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }
        .no-lawyers i { font-size: 4rem; color: rgba(197,160,89,0.3); margin-bottom: 20px; }
        .no-lawyers p { color: var(--text-body); }

        @media (max-width: 900px) {
            .lawyer-card,
            .lawyer-card.reverse {
                grid-template-columns: 1fr;
            }

            .lawyer-card.reverse .lawyer-img-wrap {
                order: 1;
            }

            .lawyer-card.reverse .lawyer-bio {
                order: 2;
            }

            .lawyer-img-wrap {
                height: 350px;
            }

            .lawyer-bio,
            .lawyer-card.reverse .lawyer-bio {
                padding: 35px 25px;
            }

            .cred-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .lawyer-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
@endpush

@section('content')

    {{-- Page Banner --}}
    <div class="page-banner" style="margin-right: 3%; margin-top: 3%;">
        <div class="page-banner-inner">
            <h1><i class="fas fa-user-tie" style="color:var(--gold-main);margin-left:12px;"></i>درباره وکلا</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">صفحه اصلی</a>
                <i class="fas fa-chevron-right"></i>
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
                        <img src="{{ $lawyer->image_url }}" alt="{{ $lawyer->name }}">
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
                <div class="lawyer-bio {{ $index % 2 !== 0 ? 'order-1' : '' }}">
                    <span class="lawyer-tag">وکیل پایه یک دادگستری</span>
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
                            <i class="fas fa-eye"></i>
                            مشاهده پروفایل
                        </a>
                    </div>
                </div>

            </div>

        @empty
            <div class="no-lawyers">
                <i class="fas fa-user-slash"></i>
                <h3 style="color:var(--text-heading);margin-bottom:10px;font-size:1.3rem;">وکیلی ثبت نشده است</h3>
                <p>در حال حاضر اطلاعات وکلا در سیستم وارد نشده است.</p>
            </div>
        @endforelse

    </div>

    {{-- ─── Credentials Bar ─────────────────────────────────────── --}}
    @if($lawyers->isNotEmpty())
    <div class="credentials-bar">
        <h3><i class="fas fa-award" style="margin-left:8px;"></i> مدارک و افتخارات مشترک</h3>
        <div class="cred-grid">
            <div class="cred-item">
                <i class="fas fa-university"></i>
                <h4>عضویت در کانون وکلا</h4>
                <p>کانون وکلای دادگستری مرکز و اصفهان</p>
            </div>
            <div class="cred-item">
                <i class="fas fa-graduation-cap"></i>
                <h4>کارشناسی ارشد حقوق</h4>
                <p>حقوق خصوصی — دانشگاه‌های معتبر ایران</p>
            </div>
            <div class="cred-item">
                <i class="fas fa-trophy"></i>
                <h4>+{{ $lawyers->sum('experience_years') }} سال تجربه مشترک</h4>
                <p>در مراجع قضایی سراسر کشور</p>
            </div>
            <div class="cred-item">
                <i class="fas fa-handshake"></i>
                <h4>+۲۰۰۰ پرونده موفق</h4>
                <p>در انواع دعاوی حقوقی و کیفری</p>
            </div>
        </div>
    </div>

    {{-- ─── CTA ─────────────────────────────────────────────────── --}}
    <div class="lawyers-cta">
        <h2>آماده‌اید قدم اول را بردارید؟</h2>
        <p>با رزرو یک جلسه مشاوره اولیه، وضعیت پرونده خود را بررسی کنید.</p>
        <div class="cta-btns">
            <a href="{{ route('reserve.index') }}" class="btn-gold">
                <i class="fas fa-calendar-check"></i> رزرو نوبت مشاوره
            </a>
            <a href="{{ route('contact') }}" class="btn-outline-navy">
                <i class="fas fa-phone"></i> تماس با دفتر
            </a>
        </div>
    </div>
    @endif

@endsection