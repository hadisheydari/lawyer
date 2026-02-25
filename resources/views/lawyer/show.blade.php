@extends('layouts.public')
@section('title', ($lawyer['name'] ?? 'وکیل') . ' | دفتر وکالت ابدالی و جوشقانی')

@push('styles')
<style>
    .lawyer-profile-page { max-width: 1200px; margin: 0 auto; padding: 80px 20px; }

    .lawyer-profile-grid {
        display: grid; grid-template-columns: 380px 1fr;
        gap: 50px; align-items: start;
    }

    /* ─── کارت جانبی ─────────────────────────────────────────── */
    .lawyer-sidebar-card {
        background: #fff; border-radius: 28px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.07);
        overflow: hidden; position: sticky; top: 90px;
    }
    .lsc-photo {
        height: 380px; background: linear-gradient(135deg, #0a1c2e, #1e3a5f);
        display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
    }
    .lsc-photo-placeholder {
        font-size: 7rem; font-weight: 900; color: var(--gold-main);
        opacity: 0.6;
    }
    .lsc-photo img { width: 100%; height: 100%; object-fit: cover; }
    .lsc-body { padding: 30px; }
    .lsc-name { font-size: 1.5rem; font-weight: 900; color: var(--text-heading); margin-bottom: 4px; }
    .lsc-title { color: var(--gold-dark); font-size: 0.85rem; font-weight: 700; margin-bottom: 20px; }
    .lsc-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
    .lsc-stat {
        background: #fdfbf7; border-radius: 12px; padding: 14px; text-align: center;
    }
    .lsc-stat .n { font-size: 1.5rem; font-weight: 900; color: var(--navy); display: block; }
    .lsc-stat .l { font-size: 0.72rem; color: var(--text-body); margin-top: 2px; display: block; }
    .lsc-contact { display: flex; flex-direction: column; gap: 10px; }
    .lsc-contact a {
        display: flex; align-items: center; gap: 12px; padding: 13px 16px;
        border-radius: 12px; font-size: 0.88rem; font-weight: 600; transition: 0.3s;
    }
    .lsc-contact a i { width: 18px; text-align: center; }
    .lsc-contact .tel {
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff;
    }
    .lsc-contact .tel:hover { opacity: 0.9; }
    .lsc-contact .reserve {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        color: #fff;
    }
    .lsc-contact .reserve:hover { opacity: 0.9; }

    /* ─── محتوای اصلی ────────────────────────────────────────── */
    .lawyer-main { display: flex; flex-direction: column; gap: 40px; }

    .profile-section {
        background: #fff; border-radius: 24px; padding: 36px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    .profile-section h2 {
        font-size: 1.15rem; font-weight: 900; color: var(--text-heading);
        margin-bottom: 22px; display: flex; align-items: center; gap: 10px;
    }
    .profile-section h2 i { color: var(--gold-main); font-size: 1rem; }

    /* تخصص‌ها */
    .specialties-grid {
        display: flex; flex-wrap: wrap; gap: 10px;
    }
    .specialty-tag {
        padding: 8px 18px; border-radius: 30px;
        background: rgba(207,168,110,0.1); border: 1px solid rgba(207,168,110,0.3);
        color: var(--gold-dark); font-size: 0.85rem; font-weight: 700;
        display: flex; align-items: center; gap: 7px;
    }
    .specialty-tag i { font-size: 0.8rem; }

    /* بیوگرافی */
    .bio-text { color: var(--text-body); font-size: 0.95rem; line-height: 2; text-align: justify; }

    /* نمونه پرونده‌ها */
    .cases-list { display: flex; flex-direction: column; gap: 14px; }
    .case-item {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 16px; background: #fdfbf7; border-radius: 14px;
        border-right: 3px solid var(--gold-main);
    }
    .case-item i { color: var(--gold-main); margin-top: 3px; flex-shrink: 0; }
    .case-item-text h4 { font-size: 0.9rem; font-weight: 800; color: var(--text-heading); margin-bottom: 3px; }
    .case-item-text p { font-size: 0.82rem; color: var(--text-body); margin: 0; }

    @media (max-width: 900px) {
        .lawyer-profile-grid { grid-template-columns: 1fr; }
        .lawyer-sidebar-card { position: static; }
        .lsc-photo { height: 280px; }
    }
</style>
@endpush

@section('content')

<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-user-tie" style="color:var(--gold-main);margin-left:12px;"></i>{{ $lawyer['name'] }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <a href="{{ route('lawyers.index') }}">وکلا</a>
            <i class="fas fa-chevron-left"></i>
            <span>{{ $lawyer['name'] }}</span>
        </div>
    </div>
</div>

<div class="lawyer-profile-page">
    <div class="lawyer-profile-grid">

        {{-- ─── Sidebar ────────────────────────────────── --}}
        <aside>
            <div class="lawyer-sidebar-card">
                <div class="lsc-photo">
                    <div class="lsc-photo-placeholder">{{ mb_substr($lawyer['name'], 0, 1) }}</div>
                </div>
                <div class="lsc-body">
                    <div class="lsc-name">{{ $lawyer['name'] }}</div>
                    <div class="lsc-title">{{ $lawyer['title'] }}</div>
                    <div class="lsc-stats">
                        <div class="lsc-stat">
                            <span class="n">+{{ $lawyer['experience'] }}</span>
                            <span class="l">سال سابقه</span>
                        </div>
                        <div class="lsc-stat">
                            <span class="n">+{{ number_format($lawyer['cases']) }}</span>
                            <span class="l">پرونده</span>
                        </div>
                        <div class="lsc-stat">
                            <span class="n">{{ $lawyer['satisfaction'] }}٪</span>
                            <span class="l">رضایت موکل</span>
                        </div>
                        <div class="lsc-stat">
                            <span class="n">پایه ۱</span>
                            <span class="l">دادگستری</span>
                        </div>
                    </div>
                    <div class="lsc-contact">
                        <a href="tel:{{ $lawyer['phone'] }}" class="tel">
                            <i class="fas fa-phone"></i>
                            {{ $lawyer['phone'] }}
                        </a>
                        <a href="{{ route('reserve.index') }}?lawyer={{ $slug }}" class="reserve">
                            <i class="fas fa-calendar-check"></i>
                            رزرو مشاوره
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ─── Main Content ───────────────────────────── --}}
        <div class="lawyer-main">

            {{-- تخصص‌ها --}}
            <div class="profile-section">
                <h2><i class="fas fa-star"></i> حوزه‌های تخصصی</h2>
                <div class="specialties-grid">
                    @foreach($lawyer['specialties'] as $spec)
                        <div class="specialty-tag">
                            <i class="fas fa-check-circle"></i>
                            {{ $spec }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- بیوگرافی --}}
            <div class="profile-section">
                <h2><i class="fas fa-user"></i> درباره {{ $lawyer['name'] }}</h2>
                <div class="bio-text">
                    @if($slug === 'babak')
                    <p>
                        بابک ابدالی، وکیل پایه یک دادگستری با بیش از ۲۸ سال سابقه فعالیت در مراجع قضایی استان اصفهان،
                        از برجسته‌ترین وکلای متخصص در حوزه‌های تجاری، ملکی، کیفری و اداری است.
                    </p>
                    <p style="margin-top:16px;">
                        ایشان با پیگیری موفق بیش از ۱۲۰۰ پرونده در سطوح مختلف قضایی، از بدوی تا دیوان عالی کشور،
                        تجربه‌ای گسترده و عملی در طیف وسیعی از دعاوی حقوقی کسب کرده‌اند.
                        رویکرد ایشان همواره بر پایه تحلیل دقیق پرونده، تهیه لوایح مستحکم و حضور فعال در جلسات دادرسی است.
                    </p>
                    <p style="margin-top:16px;">
                        آقای ابدالی عضو رسمی کانون وکلای دادگستری اصفهان و دارای مجوز وکالت پایه یک از قوه قضاییه است.
                    </p>
                    @else
                    <p>
                        زهرا جوشقانی، وکیل پایه یک دادگستری با ۲۰ سال تخصص در حوزه حقوق خانواده، ارث و ترکه،
                        یکی از معتمدترین وکلای این حوزه در استان اصفهان است.
                    </p>
                    <p style="margin-top:16px;">
                        خانم جوشقانی با موفقیت در بیش از ۸۰۰ پرونده خانوادگی از جمله طلاق، مهریه، حضانت و نفقه،
                        رویکردی انسانی، محرمانه و مؤثر در دفاع از حقوق موکلین خود دارند.
                    </p>
                    <p style="margin-top:16px;">
                        ایشان علاوه بر وکالت، در زمینه مشاوره حقوقی پیش از ازدواج و تنظیم شرایط ضمن عقد نیز
                        تجربه گسترده‌ای دارند و به صدها خانواده کمک کرده‌اند تا از بروز اختلافات پیشگیری کنند.
                    </p>
                    @endif
                </div>
            </div>

            {{-- نمونه پرونده‌ها --}}
            <div class="profile-section">
                <h2><i class="fas fa-folder-open"></i> نمونه پرونده‌های موفق</h2>
                <div class="cases-list">
                    @if($slug === 'babak')
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>وصول مطالبه ۱۵ میلیارد تومانی از شرکت ساختمانی</h4>
                            <p>پرونده اعسار و وصول وجه در دادگاه حقوقی اصفهان، نتیجه: پیروزی کامل</p>
                        </div>
                    </div>
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>ابطال سند رسمی ملک ۲۰۰۰ متری در مشهد</h4>
                            <p>اثبات جعل در سند رسمی و بازگرداندن ملک به مالک قانونی</p>
                        </div>
                    </div>
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>تبرئه متهم در پرونده کلاهبرداری ۵۰۰ میلیون تومانی</h4>
                            <p>اثبات بی‌گناهی موکل و صدور قرار منع تعقیب</p>
                        </div>
                    </div>
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>برنده‌شدن دعوی در دیوان عدالت اداری علیه شهرداری</h4>
                            <p>ابطال رأی کمیسیون ماده ۱۰۰ و تخفیف جریمه ۲۰ میلیارد تومانی</p>
                        </div>
                    </div>
                    @else
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>وصول مهریه ۵۰۰ سکه طلا به ارزش روز</h4>
                            <p>اجرای حکم از طریق توقیف اموال همسر و وصول کامل مهریه</p>
                        </div>
                    </div>
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>گرفتن حضانت فرزند ۷ ساله برای مادر</h4>
                            <p>اثبات شایستگی مادر و تغییر حضانت به نفع موکل</p>
                        </div>
                    </div>
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>دریافت اجرت‌المثل ۸ میلیارد تومانی</h4>
                            <p>اثبات سهم کار موکل در دوران زندگی مشترک و دریافت حق</p>
                        </div>
                    </div>
                    <div class="case-item">
                        <i class="fas fa-trophy"></i>
                        <div class="case-item-text">
                            <h4>انحصار وراثت در پرونده پیچیده با ۱۲ وارث</h4>
                            <p>تنظیم انحصار وراثت و تقسیم ترکه بین وراث با رضایت کامل</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>{{-- /.lawyer-main --}}
    </div>{{-- /.grid --}}
</div>

@endsection