@extends('layouts.public')
@section('title', 'حوزه‌های وکالت | ابدالی و جوشقانی')

@push('styles')
<style>
    .services-page { max-width: 1200px; margin: 0 auto; padding: 80px 20px; }

    /* ─── Grid کارت‌های خدمات ───────────────────────────────────── */
    .services-grid-page {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 30px;
    }

    .service-card-page {
        background: #fff; border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        overflow: hidden; transition: 0.4s ease;
        border: 1px solid rgba(0,0,0,0.04);
        display: flex; flex-direction: column;
        text-decoration: none; color: inherit;
    }
    .service-card-page:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 45px rgba(0,0,0,0.1);
        border-color: var(--gold-main);
    }

    .sc-header {
        padding: 35px 30px 25px;
        display: flex; align-items: flex-start; gap: 20px;
        border-bottom: 1px solid #f5f0ea;
        position: relative; overflow: hidden;
    }
    .sc-header::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, var(--gold-main), var(--gold-dark));
        transform: scaleX(0); transform-origin: right;
        transition: 0.4s;
    }
    .service-card-page:hover .sc-header::before { transform: scaleX(1); }

    .sc-icon {
        width: 62px; height: 62px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        border-radius: 16px; display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: var(--gold-main);
        box-shadow: 0 8px 20px rgba(16,42,67,0.2);
        transition: 0.3s;
    }
    .service-card-page:hover .sc-icon {
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff;
    }
    .sc-title-wrap h3 {
        font-size: 1.2rem; font-weight: 800; color: var(--text-heading);
        margin-bottom: 5px;
    }
    .sc-title-wrap span {
        font-size: 0.78rem; color: var(--gold-dark); font-weight: 600;
    }

    .sc-body { padding: 25px 30px; flex-grow: 1; }
    .sc-body p { font-size: 0.9rem; color: var(--text-body); line-height: 1.85; text-align: justify; margin-bottom: 20px; }

    .sc-cases { display: flex; flex-direction: column; gap: 8px; }
    .sc-case {
        display: flex; align-items: center; gap: 10px;
        font-size: 0.85rem; color: var(--text-body);
    }
    .sc-case i { color: var(--gold-main); font-size: 0.75rem; width: 16px; }

    .sc-footer {
        padding: 20px 30px;
        border-top: 1px solid #f5f0ea;
        display: flex; align-items: center; justify-content: space-between;
    }
    .sc-lawyer { display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: var(--text-body); }
    .sc-lawyer-dot {
        width: 30px; height: 30px; border-radius: 50%;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem; color: #fff; font-weight: 800;
    }
    .sc-arrow {
        color: var(--gold-main); font-size: 0.85rem; font-weight: 700;
        display: flex; align-items: center; gap: 5px; transition: 0.3s;
    }
    .service-card-page:hover .sc-arrow { gap: 10px; }

    /* ─── Process section ────────────────────────────────────────── */
    .process-section {
        background: #fff; padding: 80px 20px; margin-top: 0;
    }
    .process-inner { max-width: 1000px; margin: 0 auto; }
    .section-header-sm { text-align: center; margin-bottom: 50px; }
    .section-header-sm h2 {
        font-size: 1.8rem; font-weight: 900; color: var(--text-heading); margin-bottom: 10px;
    }
    .process-steps {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 20px; position: relative;
    }
    .process-steps::before {
        content: ''; position: absolute;
        top: 35px; right: 12.5%; left: 12.5%;
        height: 2px; background: linear-gradient(90deg, var(--gold-main), var(--gold-dark));
        z-index: 0;
    }
    .step {
        text-align: center; position: relative; z-index: 1;
    }
    .step-num {
        width: 70px; height: 70px; border-radius: 50%;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        color: #fff; font-size: 1.3rem; font-weight: 900;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 18px;
        border: 4px solid #fdfbf7;
        box-shadow: 0 5px 20px rgba(16,42,67,0.25);
    }
    .step h4 { font-size: 0.95rem; font-weight: 800; color: var(--text-heading); margin-bottom: 8px; }
    .step p { font-size: 0.8rem; color: var(--text-body); line-height: 1.7; }

    @media (max-width: 768px) {
        .process-steps { grid-template-columns: repeat(2, 1fr); }
        .process-steps::before { display: none; }
    }
    @media (max-width: 500px) {
        .services-grid-page { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-balance-scale" style="color:var(--gold-main);margin-left:12px;"></i>حوزه‌های وکالت</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <span>حوزه‌های وکالت</span>
        </div>
    </div>
</div>

<div class="services-page">

    <div class="services-grid-page">

        @php
        $services = [
            [
                'icon'    => 'fas fa-briefcase',
                'title'   => 'دعاوی تجاری',
                'label'   => 'Commercial Law',
                'lawyer'  => ['name' => 'ب. ابدالی', 'initial' => 'ب'],
                'desc'    => 'از تنظیم قراردادهای تجاری تا حل‌وفصل اختلافات شرکتی، ما در کنار کسب‌وکارهای شما ایستاده‌ایم. تجربه بیش از دو دهه در این حوزه، موفقیت پرونده‌های پیچیده را ممکن کرده است.',
                'cases'   => ['وصول مطالبات شرکتی', 'تنظیم و انحلال شرکت', 'ورشکستگی و اعسار', 'دعاوی ناشی از چک و برات', 'اختلافات قراردادی'],
                'slug'    => 'commercial',
            ],
            [
                'icon'    => 'fas fa-city',
                'title'   => 'دعاوی ملکی',
                'label'   => 'Real Estate Law',
                'lawyer'  => ['name' => 'ب. ابدالی', 'initial' => 'ب'],
                'desc'    => 'پرونده‌های ملکی از پیچیده‌ترین دعاوی حقوقی هستند. با تجربه گسترده در دادگاه‌های حقوقی اصفهان، وضعیت ملک شما را به نفع‌تان تغییر می‌دهیم.',
                'cases'   => ['خلع ید و تخلیه', 'الزام به تنظیم سند رسمی', 'ابطال معامله', 'دعاوی شهرداری', 'تفکیک و افراز'],
                'slug'    => 'real-estate',
            ],
            [
                'icon'    => 'fas fa-heart',
                'title'   => 'حقوق خانواده',
                'label'   => 'Family Law',
                'lawyer'  => ['name' => 'ز. جوشقانی', 'initial' => 'ز'],
                'desc'    => 'با رویکردی انسانی و محرمانه، در حساس‌ترین مسائل خانوادگی‌تان همراه شما هستیم. از مشاوره تا اجرای حکم، مسیر را برایتان هموار می‌کنیم.',
                'cases'   => ['طلاق توافقی و غیرتوافقی', 'مطالبه مهریه', 'حضانت و ملاقات فرزند', 'نفقه و اجرت‌المثل', 'شرط ضمن عقد'],
                'slug'    => 'family',
            ],
            [
                'icon'    => 'fas fa-scroll',
                'title'   => 'ارث و ترکه',
                'label'   => 'Inheritance Law',
                'lawyer'  => ['name' => 'ز. جوشقانی', 'initial' => 'ز'],
                'desc'    => 'انحصار وراثت، تقسیم ترکه و پیگیری مطالبات وراث از جمله مواردی است که تخصص ویژه‌ای می‌طلبد. در این مسیر تنها نیستید.',
                'cases'   => ['انحصار وراثت', 'تقسیم ترکه', 'ابطال وصیت‌نامه', 'مطالبه سهم‌الارث', 'دعاوی شرکای ملکی'],
                'slug'    => 'inheritance',
            ],
            [
                'icon'    => 'fas fa-gavel',
                'title'   => 'دعاوی کیفری',
                'label'   => 'Criminal Law',
                'lawyer'  => ['name' => 'ب. ابدالی', 'initial' => 'ب'],
                'desc'    => 'دفاع از متهمان و احقاق حق شاکیان در پرونده‌های کیفری، نیازمند وکیلی است که هم قانون را بشناسد، هم دادگاه را. بیست سال حضور در اتاق دادرسی تجربه ما را ساخته است.',
                'cases'   => ['کلاهبرداری و فریب', 'خیانت در امانت', 'جعل اسناد', 'ضرب و جرح', 'دعاوی ناشی از تصادف'],
                'slug'    => 'criminal',
            ],
            [
                'icon'    => 'fas fa-building',
                'title'   => 'حقوق اداری',
                'label'   => 'Administrative Law',
                'lawyer'  => ['name' => 'ب. ابدالی', 'initial' => 'ب'],
                'desc'    => 'شکایت از آرای کمیسیون‌های دولتی، دیوان عدالت اداری و دفاع در برابر تصمیمات ناعادلانه سازمان‌های دولتی از تخصص‌های دفتر ماست.',
                'cases'   => ['دیوان عدالت اداری', 'شکایت از شهرداری', 'کمیسیون ماده ۱۰۰', 'اعتراض به جریمه‌های اداری', 'دعاوی مالیاتی'],
                'slug'    => 'administrative',
            ],
        ];
        @endphp

        @foreach($services as $s)
        <div class="service-card-page">
            <div class="sc-header">
                <div class="sc-icon"><i class="{{ $s['icon'] }}"></i></div>
                <div class="sc-title-wrap">
                    <h3>{{ $s['title'] }}</h3>
                    <span>{{ $s['label'] }}</span>
                </div>
            </div>
            <div class="sc-body">
                <p>{{ $s['desc'] }}</p>
                <div class="sc-cases">
                    @foreach($s['cases'] as $case)
                        <div class="sc-case">
                            <i class="fas fa-check-circle"></i>
                            {{ $case }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="sc-footer">
                <div class="sc-lawyer">
                    <div class="sc-lawyer-dot">{{ $s['lawyer']['initial'] }}</div>
                    {{ $s['lawyer']['name'] }}
                </div>
                <a href="{{ route('reserve.index') }}?service={{ $s['slug'] }}" class="sc-arrow">
                    رزرو مشاوره <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
        @endforeach

    </div>
</div>

{{-- ─── روند کار ──────────────────────────────────────────────── --}}
<div class="process-section">
    <div class="process-inner">
        <div class="section-header-sm">
            <h2>روند همکاری با ما</h2>
            <p style="color:var(--text-body);">از اولین تماس تا نتیجه نهایی، در کنار شما هستیم</p>
        </div>
        <div class="process-steps">
            <div class="step">
                <div class="step-num">۱</div>
                <h4>مشاوره اولیه</h4>
                <p>بررسی وضعیت پرونده و ارائه راهکارهای ممکن در اولین جلسه</p>
            </div>
            <div class="step">
                <div class="step-num">۲</div>
                <h4>تحلیل پرونده</h4>
                <p>بررسی مدارک، سابقه قضایی و تعیین استراتژی دفاعی</p>
            </div>
            <div class="step">
                <div class="step-num">۳</div>
                <h4>پیگیری حقوقی</h4>
                <p>تنظیم لوایح، حضور در جلسات دادگاه و پیگیری مرحله به مرحله</p>
            </div>
            <div class="step">
                <div class="step-num">۴</div>
                <h4>نتیجه‌گیری</h4>
                <p>اجرای حکم و پیگیری تا وصول کامل حق موکل</p>
            </div>
        </div>
    </div>
</div>

@endsection
