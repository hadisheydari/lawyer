@extends('layouts.client')

@section('title', 'داشبورد')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2639 100%);
        color: #fff; padding: 35px 40px; border-radius: 15px;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px;
    }
    .welcome-card::after {
        content: ''; position: absolute; right: -20px; top: -50%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(197,160,89,0.15) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none;
    }
    .welcome-text h2 { font-size: 1.5rem; margin: 0 0 8px; font-weight: 800; }
    .welcome-text p  { opacity: 0.75; margin: 0 0 12px; font-size: 0.9rem; }
    .user-type-tag {
        background: rgba(197,160,89,0.2); border: 1px solid var(--gold-main);
        color: var(--gold-main); padding: 4px 14px; border-radius: 20px;
        font-size: 0.82rem; display: inline-flex; align-items: center; gap: 6px;
    }
    .quick-btns { display: flex; gap: 12px; flex-wrap: wrap; }
    .q-btn {
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        color: #fff; padding: 10px 18px; border-radius: 8px;
        cursor: pointer; transition: 0.3s; display: flex; align-items: center;
        gap: 8px; font-family: 'Vazirmatn', sans-serif; font-size: 0.88rem;
        text-decoration: none;
    }
    .q-btn:hover { background: var(--gold-main); border-color: var(--gold-main); color: var(--navy); }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px; margin-bottom: 25px;
    }
    .stat-card {
        background: #fff; padding: 22px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-bottom: 3px solid transparent; transition: 0.3s;
    }
    .stat-card:hover { transform: translateY(-4px); border-bottom-color: var(--gold-main); }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; margin-bottom: 12px;
    }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: var(--navy); display: block; line-height: 1; }
    .stat-label { font-size: 0.85rem; color: #888; margin-top: 6px; display: block; }

    .section-box {
        background: #fff; padding: 25px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;
    }
    .section-title {
        font-size: 1.05rem; font-weight: 800; color: var(--navy);
        margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;
        padding-bottom: 15px; border-bottom: 2px solid #f5f0ea;
    }
    .view-all { font-size: 0.82rem; color: var(--gold-dark); text-decoration: none; display: flex; align-items: center; gap: 5px; }
    .view-all:hover { color: var(--gold-main); }

    .consult-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 0; border-bottom: 1px solid #f5f5f5; gap: 15px;
    }
    .consult-item:last-child { border-bottom: none; }
    .consult-left { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; }
    .consult-icon {
        width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .consult-info h4 {
        font-size: 0.9rem; font-weight: 700; color: var(--navy);
        margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .consult-info span { font-size: 0.78rem; color: #888; }

    .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; flex-shrink: 0; }
    .badge-pending     { background: #fef3c7; color: #b45309; }
    .badge-confirmed   { background: #dbeafe; color: #1d4ed8; }
    .badge-in_progress { background: #ede9fe; color: #6d28d9; }
    .badge-completed   { background: #d1fae5; color: #065f46; }
    .badge-cancelled   { background: #fee2e2; color: #b91c1c; }
    .badge-rejected    { background: #fee2e2; color: #b91c1c; }

    .empty-state { text-align: center; padding: 50px 20px; color: #aaa; }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 15px; opacity: 0.4; }
    .empty-state p { font-size: 0.9rem; margin-bottom: 20px; }

    .quick-links-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px; margin-bottom: 25px;
    }
    .quick-link-card {
        background: #fff; border-radius: 12px; padding: 20px 15px;
        text-align: center; text-decoration: none; color: var(--navy);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid transparent; transition: 0.3s;
    }
    .quick-link-card:hover { border-color: var(--gold-main); transform: translateY(-3px); color: var(--gold-dark); }
    .quick-link-card i { font-size: 1.5rem; display: block; margin-bottom: 8px; color: var(--gold-main); }
    .quick-link-card span { font-size: 0.85rem; font-weight: 600; }

    .cta-box {
        background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
        border-radius: 12px; padding: 30px; color: #fff; text-align: center;
    }
    .cta-box h3 { font-size: 1.15rem; margin-bottom: 8px; font-weight: 800; }
    .cta-box p  { opacity: 0.7; font-size: 0.88rem; margin-bottom: 20px; }
    .btn-gold {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; padding: 12px 24px; border-radius: 10px;
        font-weight: 700; font-size: 0.9rem; text-decoration: none;
        box-shadow: 0 5px 15px rgba(197,160,89,0.3); transition: 0.3s;
    }
    .btn-gold:hover { transform: translateY(-2px); color: #fff; }

    @media (max-width: 768px) {
        .welcome-card { flex-direction: column; gap: 20px; }
        .quick-btns { justify-content: center; }
    }
</style>
@endpush

@section('content')

    <div class="welcome-card">
        <div class="welcome-text">
            <h2>خوش آمدید، {{ $user->name }}</h2>
            <p>پنل مدیریت مشاوره‌های حقوقی شما</p>
            <span class="user-type-tag"><i class="fas fa-user"></i> مشتری عادی</span>
        </div>
        <div class="quick-btns">
            <a href="{{ route('reserve.index') }}" class="q-btn">
                <i class="fas fa-calendar-plus"></i> رزرو نوبت جدید
            </a>
            <a href="{{ route('client.chat.index') }}" class="q-btn">
                <i class="fas fa-comment-dots"></i> پیام‌ها
                @if($unreadMessages > 0)
                    <span style="background:#e74c3c;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:0.65rem;">
                        {{ $unreadMessages }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="fas fa-comments"></i></div>
            <span class="stat-value">{{ $activeConsultations }}</span>
            <span class="stat-label">مشاوره‌های فعال</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;color:#065f46;"><i class="fas fa-check-double"></i></div>
            <span class="stat-value">{{ $completedConsultations }}</span>
            <span class="stat-label">مشاوره‌های تکمیل‌شده</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><i class="far fa-envelope"></i></div>
            <span class="stat-value">{{ $unreadMessages }}</span>
            <span class="stat-label">پیام خوانده‌نشده</span>
        </div>
    </div>

    <div class="quick-links-grid">
        <a href="{{ route('reserve.index') }}" class="quick-link-card">
            <i class="fas fa-calendar-check"></i><span>رزرو نوبت</span>
        </a>
        <a href="{{ route('client.consultations.index') }}" class="quick-link-card">
            <i class="fas fa-list-ul"></i><span>همه مشاوره‌ها</span>
        </a>
        <a href="{{ route('client.chat.index') }}" class="quick-link-card">
            <i class="fas fa-comment-dots"></i><span>پیام به وکیل</span>
        </a>
        <a href="{{ route('client.profile') }}" class="quick-link-card">
            <i class="fas fa-user-edit"></i><span>پروفایل</span>
        </a>
    </div>

    <div class="section-box">
        <div class="section-title">
            <span><i class="fas fa-history" style="color:var(--gold-main);margin-left:8px;"></i>آخرین مشاوره‌ها</span>
            <a href="{{ route('client.consultations.index') }}" class="view-all">
                مشاهده همه <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        @forelse($consultations as $consultation)
            @php
                $typeIcons = [
                    'chat'        => ['icon' => 'fas fa-comment',       'bg' => '#dbeafe', 'color' => '#1d4ed8'],
                    'call'        => ['icon' => 'fas fa-phone',          'bg' => '#d1fae5', 'color' => '#065f46'],
                    'appointment' => ['icon' => 'fas fa-calendar-check', 'bg' => '#ede9fe', 'color' => '#6d28d9'],
                ];
                $typeStyle = $typeIcons[$consultation->type] ?? ['icon' => 'fas fa-file', 'bg' => '#f3f4f6', 'color' => '#6b7280'];

                $statusMap = [
                    'pending'     => ['label' => 'در انتظار تأیید', 'class' => 'badge-pending'],
                    'confirmed'   => ['label' => 'تأیید شده',       'class' => 'badge-confirmed'],
                    'in_progress' => ['label' => 'در حال انجام',    'class' => 'badge-in_progress'],
                    'completed'   => ['label' => 'تکمیل شده',       'class' => 'badge-completed'],
                    'cancelled'   => ['label' => 'لغو شده',          'class' => 'badge-cancelled'],
                    'rejected'    => ['label' => 'رد شده',           'class' => 'badge-rejected'],
                ];
                $s = $statusMap[$consultation->status] ?? ['label' => $consultation->status, 'class' => ''];
            @endphp
            <div class="consult-item">
                <div class="consult-left">
                    <div class="consult-icon" style="background:{{ $typeStyle['bg'] }};color:{{ $typeStyle['color'] }};">
                        <i class="{{ $typeStyle['icon'] }}"></i>
                    </div>
                    <div class="consult-info">
                        <h4>{{ $consultation->title }}</h4>
                        <span>
                            {{ $consultation->lawyer->name ?? '—' }}
                            @if($consultation->scheduled_at)
                                &nbsp;·&nbsp;
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->scheduled_at)->format('Y/m/d') }}
                                ساعت {{ $consultation->scheduled_at->format('H:i') }}
                            @endif
                        </span>
                    </div>
                </div>
                <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-comments" style="color:var(--gold-light);"></i>
                <p>هنوز هیچ مشاوره‌ای ثبت نشده است</p>
                <a href="{{ route('reserve.index') }}" class="btn-gold">
                    <i class="fas fa-calendar-plus"></i> اولین نوبت را رزرو کنید
                </a>
            </div>
        @endforelse
    </div>

    <div class="cta-box">
        <h3><i class="fas fa-star" style="color:var(--gold-main);margin-left:8px;"></i>نیاز به مشاوره تخصصی دارید؟</h3>
        <p>وکلای متخصص ما آماده بررسی پرونده و پاسخگویی به سوالات حقوقی شما هستند</p>
        <a href="{{ route('reserve.index') }}" class="btn-gold">
            <i class="fas fa-calendar-check"></i> رزرو مشاوره حضوری
        </a>
    </div>

@endsection