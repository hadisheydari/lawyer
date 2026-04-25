@extends('layouts.public')
@section('title', $lawyer->name . ' | دفتر وکالت ابدالی و جوشقانی')

@push('styles')
<style>
    .lawyer-profile-page { max-width: 1200px; margin: 0 auto; padding: 80px 20px; }

    .lawyer-profile-grid {
        display: grid; grid-template-columns: 380px 1fr;
        gap: 50px; align-items: start;
    }

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
        text-decoration: none;
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

    .specialties-grid { display: flex; flex-wrap: wrap; gap: 10px; }
    .specialty-tag {
        padding: 8px 18px; border-radius: 30px;
        background: rgba(207,168,110,0.1); border: 1px solid rgba(207,168,110,0.3);
        color: var(--gold-dark); font-size: 0.85rem; font-weight: 700;
        display: flex; align-items: center; gap: 7px;
    }
    .bio-text { color: var(--text-body); font-size: 0.95rem; line-height: 2; text-align: justify; }
    .bio-text p { margin-bottom: 15px; }

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
        <h1><i class="fas fa-user-tie" style="color:var(--gold-main);margin-left:12px;"></i>{{ $lawyer->name }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <a href="{{ route('lawyers.index') }}">وکلا</a>
            <i class="fas fa-chevron-left"></i>
            <span>{{ $lawyer->name }}</span>
        </div>
    </div>
</div>

<div class="lawyer-profile-page">
    <div class="lawyer-profile-grid">

        {{-- Sidebar --}}
        <aside>
            <div class="lawyer-sidebar-card">
                <div class="lsc-photo">
                    @if($lawyer->image)
                        <img src="{{ $lawyer->image_url }}" alt="{{ $lawyer->name }}">
                    @else
                        <div class="lsc-photo-placeholder">{{ mb_substr($lawyer->name, 0, 1) }}</div>
                    @endif
                </div>
                <div class="lsc-body">
                    <div class="lsc-name">{{ $lawyer->name }}</div>
                    <div class="lsc-title">وکیل پایه {{ $lawyer->license_grade }} دادگستری</div>
                    <div class="lsc-stats">
                        <div class="lsc-stat">
                            <span class="n">+{{ $lawyer->experience_years }}</span>
                            <span class="l">سال سابقه</span>
                        </div>
                        <div class="lsc-stat">
                            <span class="n">پایه {{ $lawyer->license_grade }}</span>
                            <span class="l">دادگستری</span>
                        </div>
                    </div>
                    <div class="lsc-contact">
                        @if($lawyer->phone)
                            <a href="tel:{{ $lawyer->phone }}" class="tel">
                                <i class="fas fa-phone"></i>
                                {{ $lawyer->phone }}
                            </a>
                        @endif
                        <a href="{{ route('reserve.index', ['lawyer' => $lawyer->slug]) }}" class="reserve">
                            <i class="fas fa-calendar-check"></i>
                            رزرو مشاوره
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="lawyer-main">

            {{-- تخصص‌ها --}}
            @if($lawyer->specializations && count($lawyer->specializations) > 0)
            <div class="profile-section">
                <h2><i class="fas fa-star"></i> حوزه‌های تخصصی</h2>
                <div class="specialties-grid">
                    @foreach($lawyer->specializations as $spec)
                        <div class="specialty-tag">
                            <i class="fas fa-check-circle"></i>
                            {{ $spec }}
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- بیوگرافی --}}
            @if($lawyer->bio)
            <div class="profile-section">
                <h2><i class="fas fa-user"></i> درباره {{ $lawyer->name }}</h2>
                <div class="bio-text">
                    {!! nl2br(e($lawyer->bio)) !!}
                </div>
            </div>
            @endif

            {{-- تحصیلات --}}
            @if($lawyer->education)
            <div class="profile-section">
                <h2><i class="fas fa-graduation-cap"></i> تحصیلات</h2>
                <div class="bio-text">
                    <p>{{ $lawyer->education }}</p>
                </div>
            </div>
            @endif

            {{-- دکمه رزرو --}}
            <div class="profile-section" style="text-align:center;padding:30px;">
                <a href="{{ route('reserve.index', ['lawyer' => $lawyer->slug]) }}"
                   style="display:inline-flex;align-items:center;gap:10px;background:linear-gradient(135deg,var(--navy),#1e3a5f);color:#fff;padding:15px 35px;border-radius:14px;font-weight:700;font-size:1rem;text-decoration:none;box-shadow:0 8px 20px rgba(16,42,67,0.2);">
                    <i class="fas fa-calendar-check"></i>
                    رزرو وقت مشاوره با {{ $lawyer->name }}
                </a>
            </div>

        </div>
    </div>
</div>

@endsection