@extends('layouts.public')
@section('title', 'درباره وکلا | ابدالی و جوشقانی')

@push('styles')
<style>
    .lawyers-intro {
        max-width: 1200px; margin: 0 auto; padding: 80px 20px;
    }

    /* ─── کارت وکیل ──────────────────────────────────────────────── */
    .lawyer-card {
        display: grid; grid-template-columns: 380px 1fr;
        gap: 60px; align-items: start;
        background: #fff; border-radius: 28px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.07);
        overflow: hidden; margin-bottom: 50px;
        transition: transform 0.4s ease;
    }
    .lawyer-card:hover { transform: translateY(-5px); }
    .lawyer-card.reverse { grid-template-columns: 1fr 380px; }
    .lawyer-card.reverse .lawyer-img-wrap { order: 2; }
    .lawyer-card.reverse .lawyer-bio { order: 1; }

    .lawyer-img-wrap {
        position: relative; height: 520px; overflow: hidden;
        background: linear-gradient(135deg, #0a1c2e, #1e3a5f);
    }
    .lawyer-img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        opacity: 0.85; filter: contrast(1.05) saturate(0.85);
        transition: 0.5s;
    }
    .lawyer-card:hover .lawyer-img-wrap img { opacity: 1; transform: scale(1.03); }

    .lawyer-badge {
        position: absolute; bottom: 25px; right: 25px;
        background: rgba(207,168,110,0.95); color: #fff;
        padding: 10px 20px; border-radius: 50px;
        font-size: 0.82rem; font-weight: 700;
        display: flex; align-items: center; gap: 8px;
        backdrop-filter: blur(5px);
    }

    .lawyer-bio { padding: 50px 50px 50px 20px; }
    .lawyer-card.reverse .lawyer-bio { padding: 50px 20px 50px 50px; }

    .lawyer-tag {
        display: inline-block; background: rgba(207,168,110,0.12);
        color: var(--gold-dark); font-size: 0.78rem; font-weight: 700;
        padding: 4px 14px; border-radius: 30px;
        margin-bottom: 15px; letter-spacing: 0.5px;
    }
    .lawyer-bio h2 {
        font-size: 2rem; font-weight: 900; color: var(--text-heading);
        margin-bottom: 8px; line-height: 1.2;
    }
    .lawyer-bio .title-line {
        font-size: 0.95rem; color: var(--gold-main); font-weight: 600;
        margin-bottom: 25px; display: flex; align-items: center; gap: 8px;
    }
    .lawyer-bio p {
        color: var(--text-body); font-size: 0.95rem;
        line-height: 1.95; text-align: justify; margin-bottom: 30px;
    }

    /* آمار وکیل */
    .lawyer-stats {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 15px; margin-bottom: 30px;
    }
    .ls-item {
        background: #fdfbf7; border-radius: 12px; padding: 18px;
        text-align: center; border: 1px solid rgba(207,168,110,0.15);
    }
    .ls-num { font-size: 1.7rem; font-weight: 900; color: var(--navy); display: block; }
    .ls-label { font-size: 0.75rem; color: var(--text-body); margin-top: 3px; display: block; }

    /* تخصص‌ها */
    .expertise-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 30px; }
    .exp-tag {
        background: #f0f4f8; color: var(--navy);
        padding: 6px 16px; border-radius: 30px;
        font-size: 0.8rem; font-weight: 600;
        border: 1px solid rgba(16,42,67,0.1);
        transition: 0.3s;
    }
    .exp-tag:hover { background: var(--navy); color: #fff; }

    .btn-contact-lawyer {
        display: inline-flex; align-items: center; gap: 10px;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        color: #fff; padding: 13px 28px; border-radius: 12px;
        font-weight: 700; font-size: 0.92rem;
        box-shadow: 0 8px 20px rgba(16,42,67,0.2);
        transition: 0.3s;
    }
    .btn-contact-lawyer:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(16,42,67,0.3);
        color: #fff;
    }

    /* ─── Bar شهادت‌نامه‌ها ─────────────────────────────────────── */
    .credentials-bar {
        background: var(--navy); padding: 50px 20px;
        text-align: center;
    }
    .credentials-bar h3 {
        color: var(--gold-main); font-size: 1.1rem;
        margin-bottom: 30px; font-weight: 700;
    }
    .cred-grid {
        max-width: 900px; margin: 0 auto;
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
    }
    .cred-item {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(207,168,110,0.2);
        border-radius: 14px; padding: 25px 15px;
        color: #fff; transition: 0.3s;
    }
    .cred-item:hover { background: rgba(207,168,110,0.12); border-color: var(--gold-main); }
    .cred-item i { font-size: 1.8rem; color: var(--gold-main); margin-bottom: 12px; display: block; }
    .cred-item h4 { font-size: 0.88rem; font-weight: 700; margin-bottom: 5px; }
    .cred-item p { font-size: 0.75rem; color: rgba(255,255,255,0.5); }

    /* ─── CTA پایین صفحه ──────────────────────────────────────── */
    .lawyers-cta {
        max-width: 1200px; margin: 80px auto; padding: 0 20px;
        text-align: center;
    }
    .lawyers-cta h2 { font-size: 1.8rem; color: var(--text-heading); font-weight: 900; margin-bottom: 15px; }
    .lawyers-cta p { color: var(--text-body); margin-bottom: 30px; }
    .cta-btns { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
    .btn-gold {
        display: inline-flex; align-items: center; gap: 10px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; padding: 14px 32px; border-radius: 12px;
        font-weight: 700; font-size: 0.95rem;
        box-shadow: 0 8px 20px rgba(207,168,110,0.35);
        transition: 0.3s; text-decoration: none;
    }
    .btn-gold:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(207,168,110,0.5); color: #fff; }
    .btn-outline-navy {
        display: inline-flex; align-items: center; gap: 10px;
        border: 2px solid var(--navy); color: var(--navy);
        padding: 12px 28px; border-radius: 12px;
        font-weight: 700; font-size: 0.95rem; transition: 0.3s;
        text-decoration: none;
    }
    .btn-outline-navy:hover { background: var(--navy); color: #fff; }

    @media (max-width: 900px) {
        .lawyer-card, .lawyer-card.reverse { grid-template-columns: 1fr; }
        .lawyer-card.reverse .lawyer-img-wrap { order: 1; }
        .lawyer-card.reverse .lawyer-bio { order: 2; }
        .lawyer-img-wrap { height: 350px; }
        .lawyer-bio, .lawyer-card.reverse .lawyer-bio { padding: 35px 25px; }
        .cred-grid { grid-template-columns: repeat(2, 1fr); }
        .lawyer-stats { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('content')

{{-- Page Banner --}}
<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-user-tie" style="color:var(--gold-main);margin-left:12px;"></i>درباره وکلا</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <span>درباره وکلا</span>
        </div>
    </div>
</div>

<div class="lawyers-intro">

    {{-- ═══ وکیل اول: بابک ابدالی ═══════════════════════════════ --}}
    <div class="lawyer-card">
        <div class="lawyer-img-wrap">
            <img src="{{ asset('images/babak.jpg') }}"
                 onerror="this.parentElement.style.background='linear-gradient(160deg,#102a43,#1e3a5f)'"
                 alt="بابک ابدالی">
            <div class="lawyer-badge">
                <i class="fas fa-certificate"></i> وکیل پایه یک دادگستری
            </div>
        </div>

        <div class="lawyer-bio">
            <span class="lawyer-tag">وکیل ارشد دفتر</span>
            <h2>بابک ابدالی</h2>
            <div class="title-line">
                <i class="fas fa-gavel"></i>
                وکیل پایه یک دادگستری — کانون وکلای مرکز
            </div>

            <div class="lawyer-stats">
                <div class="ls-item">
                    <span class="ls-num">۲۸+</span>
                    <span class="ls-label">سال سابقه</span>
                </div>
                <div class="ls-item">
                    <span class="ls-num">+۱۲۰۰</span>
                    <span class="ls-label">پرونده موفق</span>
                </div>
                <div class="ls-item">
                    <span class="ls-num">۹۷٪</span>
                    <span class="ls-label">رضایت موکلین</span>
                </div>
            </div>

            <p>
                بابک ابدالی با بیش از دو دهه و نیم تجربه در دادگاه‌های حقوقی و کیفری ایران، یکی از
                شناخته‌شده‌ترین وکلای اصفهان در حوزه دعاوی تجاری و ملکی است. ایشان پس از فارغ‌التحصیلی
                از دانشکده حقوق دانشگاه اصفهان، تحصیلات تکمیلی خود را در مقطع کارشناسی ارشد حقوق
                خصوصی گذرانده و از آن پس به طور تخصصی در دفاع از حقوق موکلین در برابر نهادهای دولتی
                و خصوصی فعالیت می‌کند.
            </p>
            <p>
                تسلط عمیق بر قوانین تجاری، مهارت مذاکره خارج از دادگاه، و سابقه موفق در پرونده‌های
                پیچیده چند‌مرحله‌ای، ابدالی را به انتخاب اول شرکت‌ها و کسب‌وکارها در اصفهان تبدیل کرده است.
            </p>

            <div class="expertise-tags">
                <span class="exp-tag">دعاوی تجاری</span>
                <span class="exp-tag">قراردادها</span>
                <span class="exp-tag">ورشکستگی</span>
                <span class="exp-tag">چک و برات</span>
                <span class="exp-tag">دعاوی ملکی</span>
                <span class="exp-tag">اجرای احکام</span>
            </div>

            <a href="{{ route('reserve.index') }}?lawyer=babak" class="btn-contact-lawyer">
                <i class="fas fa-calendar-check"></i>
                رزرو وقت با بابک ابدالی
            </a>
        </div>
    </div>

    {{-- ═══ وکیل دوم: زهرا جوشقانی ══════════════════════════════ --}}
    <div class="lawyer-card reverse">
        <div class="lawyer-bio">
            <span class="lawyer-tag">متخصص حقوق خانواده</span>
            <h2>زهرا جوشقانی</h2>
            <div class="title-line">
                <i class="fas fa-gavel"></i>
                وکیل پایه یک دادگستری — کانون وکلای اصفهان
            </div>

            <div class="lawyer-stats">
                <div class="ls-item">
                    <span class="ls-num">۲۰+</span>
                    <span class="ls-label">سال سابقه</span>
                </div>
                <div class="ls-item">
                    <span class="ls-num">+۸۰۰</span>
                    <span class="ls-label">پرونده موفق</span>
                </div>
                <div class="ls-item">
                    <span class="ls-num">۹۹٪</span>
                    <span class="ls-label">رضایت موکلین</span>
                </div>
            </div>

            <p>
                زهرا جوشقانی، وکیل پایه یک دادگستری با بیست سال سابقه تخصصی در حوزه حقوق خانواده،
                ارث و احوال شخصیه. ایشان دارای مدرک کارشناسی ارشد حقوق خصوصی از دانشگاه شهید
                بهشتی بوده و به عنوان مشاور حقوقی تعدادی از سازمان‌های دولتی اصفهان نیز فعالیت
                کرده‌اند.
            </p>
            <p>
                جوشقانی با رویکردی انسانی و مشاوره‌محور، در کنار دانش عمیق حقوقی، توانسته موفقیت
                چشمگیری در پرونده‌های حساس خانوادگی کسب کند. پرونده‌های مهریه، طلاق توافقی،
                حضانت فرزند و انحصار وراثت از حوزه‌های اصلی تخصص ایشان هستند.
            </p>

            <div class="expertise-tags">
                <span class="exp-tag">طلاق و مهریه</span>
                <span class="exp-tag">حضانت فرزند</span>
                <span class="exp-tag">نفقه</span>
                <span class="exp-tag">انحصار وراثت</span>
                <span class="exp-tag">تقسیم ترکه</span>
                <span class="exp-tag">سرپرستی</span>
            </div>

            <a href="{{ route('reserve.index') }}?lawyer=zahra" class="btn-contact-lawyer">
                <i class="fas fa-calendar-check"></i>
                رزرو وقت با زهرا جوشقانی
            </a>
        </div>

        <div class="lawyer-img-wrap">
            <img src="{{ asset('images/zahra.jpg') }}"
                 onerror="this.parentElement.style.background='linear-gradient(160deg,#2c1810,#4a2c1a)'"
                 alt="زهرا جوشقانی">
            <div class="lawyer-badge">
                <i class="fas fa-certificate"></i> وکیل پایه یک دادگستری
            </div>
        </div>
    </div>

</div>

{{-- ─── Credentials Bar ─────────────────────────────────────── --}}
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
            <h4>+۴۸ سال تجربه مشترک</h4>
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

@endsection
