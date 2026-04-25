@extends('layouts.client')

@section('title', 'داشبورد')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2639 100%);
        color: #fff; padding: 35px 40px; border-radius: 15px;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px;
    }
    .welcome-card::after {
        content: ''; position: absolute; right: -20px; top: -50%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(197,160,89,0.15) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none;
    }
    .welcome-text h2 { font-size: 1.6rem; margin: 0 0 8px; }
    .welcome-text p  { opacity: 0.8; margin: 0 0 10px; font-size: 0.9rem; }
    .simple-tag {
        background: rgba(197,160,89,0.2); border: 1px solid var(--gold-main);
        color: var(--gold-main); padding: 4px 14px;
        border-radius: 20px; font-size: 0.82rem; display: inline-block;
    }
    .quick-btns { display: flex; gap: 12px; }
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
        font-size: 1.3rem; margin-bottom: 12px;
    }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: var(--navy); display: block; line-height: 1; }
    .stat-label { font-size: 0.85rem; color: #888; margin-top: 4px; display: block; }

    .section-box {
        background: #fff; padding: 25px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;
    }
    .section-title {
        font-size: 1.05rem; font-weight: 800; color: var(--navy);
        margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;
    }
    .view-all { font-size: 0.82rem; color: var(--gold-dark); text-decoration: none; }

    .consult-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 0; border-bottom: 1px solid #f5f5f5;
    }
    .consult-item:last-child { border-bottom: none; }
    .consult-info h4 { font-size: 0.9rem; font-weight: 700; color: var(--navy); margin: 0 0 4px; }
    .consult-info span { font-size: 0.78rem; color: #888; }
    .badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-pending   { background: #fef3c7; color: #d97706; }
    .badge-confirmed { background: #dbeafe; color: #2563eb; }
    .badge-completed { background: #d1fae5; color: #059669; }
    .badge-cancelled { background: #fee2e2; color: #dc2626; }
    .badge-in_progress { background: #ede9fe; color: #7c3aed; }

    .empty-state { text-align: center; padding: 40px 20px; color: #aaa; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 12px; color: var(--gold-light); }
    .empty-state p { font-size: 0.9rem; margin-bottom: 15px; }

    .cta-card {
        background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
        border-radius: 12px; padding: 30px; color: #fff; text-align: center;
    }
    .cta-card h3 { font-size: 1.2rem; margin-bottom: 10px; }
    .cta-card p { opacity: 0.7; font-size: 0.88rem; margin-bottom: 20px; }
    .btn-gold {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; padding: 12px 24px; border-radius: 10px;
        font-weight: 700; font-size: 0.9rem; text-decoration: none;
        box-shadow: 0 5px 15px rgba(197,160,89,0.3); transition: 0.3s;
    }
    .btn-gold:hover { transform: translateY(-2px); color: #fff; }

    @media (max-width: 768px) {
        .welcome-card { flex-direction: column; gap: 15px; }
        .quick-btns { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

    {{-- Welcome --}}
    <div class="welcome-card">
        <div class="welcome-text">
            <h2>خوش آمدید، {{ $user->name }}</h2>
            <p>پنل مدیریت مشاوره‌های حقوقی شما</p>
            <span class="simple-tag"><i class="fas fa-user"></i> مشتری عادی</span>
        </div>
        <div class="quick-btns">
            <a href="{{ route('reserve.index') }}" class="q-btn">
                <i class="fas fa-calendar-plus"></i> رزرو نوبت
            </a>
            <a href="{{ route('client.chat.index') }}" class="q-btn">
                <i class="fas fa-comment-dots"></i> پیام‌ها
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="fas fa-comments"></i>
            </div>
            <span class="stat-value">{{ $activeConsultations }}</span>
            <span class="stat-label">مشاوره‌های فعال</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;color:#059669;">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="stat-value">{{ $completedConsultations }}</span>
            <span class="stat-label">مشاوره‌های تکمیل‌شده</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;">
                <i class="far fa-envelope"></i>
            </div>
            <span class="stat-value">{{ $unreadMessages }}</span>
            <span class="stat-label">پیام خوانده‌نشده</span>
        </div>
    </div>

    {{-- مشاوره‌های اخیر --}}
    <div class="section-box">
        <div class="section-title">
            آخرین مشاوره‌ها
            <a href="{{ route('client.consultations.index') }}" class="view-all">
                مشاهده همه <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        @forelse($consultations as $consultation)
            <div class="consult-item">
                <div class="consult-info">
                    <h4>{{ $consultation->title }}</h4>
                    <span>
                        {{ $consultation->lawyer->name ?? '—' }}
                        @if($consultation->scheduled_at)
                            &nbsp;—&nbsp;
                            {{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->scheduled_at)->format('Y/m/d H:i') }}
                        @endif
                    </span>
                </div>
                @php
                    $statusMap = [
                        'pending'     => ['label' => 'در انتظار', 'class' => 'badge-pending'],
                        'confirmed'   => ['label' => 'تأیید شده', 'class' => 'badge-confirmed'],
                        'in_progress' => ['label' => 'در حال انجام', 'class' => 'badge-in_progress'],
                        'completed'   => ['label' => 'تکمیل شده', 'class' => 'badge-completed'],
                        'cancelled'   => ['label' => 'لغو شده', 'class' => 'badge-cancelled'],
                        'rejected'    => ['label' => 'رد شده', 'class' => 'badge-cancelled'],
                    ];
                    $s = $statusMap[$consultation->status] ?? ['label' => $consultation->status, 'class' => ''];
                @endphp
                <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-comments"></i>
                <p>هنوز مشاوره‌ای ثبت نشده است</p>
                <a href="{{ route('reserve.index') }}" class="btn-gold">
                    <i class="fas fa-calendar-plus"></i> اولین نوبت را رزرو کنید
                </a>
            </div>
        @endforelse
    </div>

    {{-- CTA --}}
    <div class="cta-card">
        <h3><i class="fas fa-star" style="color:var(--gold-main);margin-left:8px;"></i>نیاز به مشاوره دارید؟</h3>
        <p>وکلای متخصص ما آماده پاسخگویی به سوالات حقوقی شما هستند</p>
        <a href="{{ route('reserve.index') }}" class="btn-gold">
            <i class="fas fa-calendar-check"></i> رزرو مشاوره حضوری
        </a>
    </div>

@endsection