@extends('layouts.lawyer')

@section('title', 'داشبورد مدیریت وکیل')

@push('styles')
<style>
    :root {
        --gold-main: #d4af37;
        --gold-dark: #aa8222;
        --navy: #0f172a;
        --navy-light: #1e293b;
        --bg-body: #f8fafc;
        --shadow-card: 0 10px 30px rgba(0,0,0,0.05);
        --transition: all 0.3s ease;
    }

    .dashboard-wrapper { max-width: 1300px; margin: 30px auto; padding: 0 20px; }

    /* ─── کارت خوش‌آمدگویی ─── */
    .welcome-card {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        color: #fff; padding: 40px; border-radius: 20px;
        position: relative; overflow: hidden;
        box-shadow: 0 15px 35px rgba(15,23,42,0.15);
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 30px; border-bottom: 4px solid var(--gold-main);
    }
    .welcome-card::after {
        content: '\f0e3'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
        position: absolute; left: -20px; top: -30px; font-size: 14rem;
        color: rgba(212,175,55,0.05); pointer-events: none;
    }
    .welcome-text h2 { font-size: 1.8rem; font-weight: 900; margin-bottom: 10px; color: var(--gold-main); }
    .welcome-text p { font-size: 1rem; color: #cbd5e1; margin-bottom: 0; }
    
    .quick-actions { display: flex; gap: 15px; position: relative; z-index: 2;}
    .btn-gold {
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; padding: 12px 24px; border-radius: 12px; font-weight: 800;
        display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
        box-shadow: 0 8px 20px rgba(212,175,55,0.3); transition: var(--transition);
    }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(212,175,55,0.4); color: #fff;}

    /* ─── کارت‌های آمار ─── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card {
        background: #fff; padding: 25px; border-radius: 16px;
        box-shadow: var(--shadow-card); display: flex; align-items: center; gap: 20px;
        border: 1px solid rgba(0,0,0,0.03); transition: var(--transition); position: relative;
    }
    .stat-card:hover { transform: translateY(-5px); border-color: rgba(212,175,55,0.3); }
    .stat-icon {
        width: 60px; height: 60px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0;
    }
    .stat-info h3 { font-size: 2rem; font-weight: 900; color: var(--navy); line-height: 1; margin-bottom: 5px; }
    .stat-info p { font-size: 0.9rem; color: var(--text-body); font-weight: 600; margin: 0; }
    
    .badge-pulse {
        position: absolute; top: 15px; left: 15px; background: #ef4444; color: white;
        font-size: 0.75rem; font-weight: bold; padding: 3px 8px; border-radius: 20px;
        animation: pulse 2s infinite;
    }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }

    /* ─── لیست‌های پنل ─── */
    .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;}

    .panel-box {
        background: #fff; border-radius: 16px; padding: 30px;
        box-shadow: var(--shadow-card); border: 1px solid rgba(0,0,0,0.03);
    }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid var(--bg-body); padding-bottom: 15px;}
    .panel-header h3 { font-size: 1.2rem; font-weight: 900; color: var(--navy); display: flex; align-items: center; gap: 10px;}
    .panel-header h3 i { color: var(--gold-main); }
    .panel-header a { font-size: 0.85rem; color: var(--gold-dark); font-weight: 700; text-decoration: none;}

    .list-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 15px 0; border-bottom: 1px dashed #e2e8f0;
    }
    .list-item:last-child { border-bottom: none; padding-bottom: 0; }
    .item-info h4 { font-size: 0.95rem; font-weight: 800; color: var(--text-heading); margin-bottom: 5px; }
    .item-info p { font-size: 0.8rem; color: var(--text-body); margin: 0; display: flex; align-items: center; gap: 5px; }
    
    .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; }
    .bg-pending { background: #fef3c7; color: #b45309; border: 1px solid rgba(180, 83, 9, 0.2);}
    .bg-confirmed { background: #dbeafe; color: #1d4ed8; border: 1px solid rgba(29, 78, 216, 0.2);}
    .bg-active { background: #d1fae5; color: #065f46; border: 1px solid rgba(6, 95, 70, 0.2);}

    .action-btn { width: 35px; height: 35px; border-radius: 10px; background: var(--bg-body); color: var(--navy); display: flex; align-items: center; justify-content: center; transition: 0.2s; text-decoration: none;}
    .action-btn:hover { background: var(--navy); color: #fff; }

    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; opacity: 0.5; color: var(--gold-main); }

    @media (max-width: 960px) {
        .content-grid { grid-template-columns: 1fr; }
        .welcome-card { flex-direction: column; align-items: flex-start; gap: 20px; }
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">

    {{-- Welcome Section --}}
    <div class="welcome-card">
        <div class="welcome-text">
            <h2>سلام، وکیل {{ $lawyer->name }}</h2>
            <p>امروز {{ \Morilog\Jalali\Jalalian::now()->format('l، d F Y') }} است. پنل مدیریت پرونده‌ها و مشاوره‌ها آماده کار است.</p>
        </div>
        <div class="quick-actions">
            <a href="#" class="btn-gold"><i class="fas fa-calendar-alt"></i> برنامه زمانی من</a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(15,23,42,0.08); color: var(--navy);"><i class="fas fa-briefcase"></i></div>
            <div class="stat-info">
                <h3>{{ $activeCasesCount }}</h3>
                <p>پرونده‌های در جریان</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(212,175,55,0.15); color: var(--gold-dark);"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-info">
                <h3>{{ $todayConsultationsCount }}</h3>
                <p>مشاوره‌های تأیید شده امروز</p>
            </div>
        </div>
        <div class="stat-card">
            @if($pendingRequestsCount > 0)
                <span class="badge-pulse">جدید</span>
            @endif
            <div class="stat-icon" style="background: #fef2f2; color: #dc2626;"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-info">
                <h3>{{ $pendingRequestsCount }}</h3>
                <p>درخواست مشاوره جدید</p>
            </div>
        </div>
        <div class="stat-card">
            @if($unreadMessagesCount > 0)
                <span class="badge-pulse">پیام جدید</span>
            @endif
            <div class="stat-icon" style="background: #dbeafe; color: #2563eb;"><i class="fas fa-comment-dots"></i></div>
            <div class="stat-info">
                <h3>{{ $unreadMessagesCount }}</h3>
                <p>پیام‌های نخوانده از موکلین</p>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="content-grid">
        
        {{-- نوبت‌های مشاوره --}}
        <div class="panel-box">
            <div class="panel-header">
                <h3><i class="fas fa-headset"></i> نوبت‌های مشاوره پیش رو</h3>
                <a href="#">مدیریت نوبت‌ها &leftarrow;</a>
            </div>
            
            <div class="list-wrapper">
                @forelse($upcomingConsultations as $consultation)
                    <div class="list-item">
                        <div class="item-info">
                            <h4>مشتری: {{ $consultation->user->name ?? 'کاربر سایت' }}</h4>
                            <p>
                                <i class="far fa-clock"></i> 
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->scheduled_at)->format('Y/m/d H:i') }}
                                | 
                                <i class="fas fa-tag"></i> 
                                @if($consultation->type == 'chat') چت @elseif($consultation->type == 'call') تلفنی @else حضوری @endif
                            </p>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span class="badge {{ $consultation->status == 'pending' ? 'bg-pending' : 'bg-confirmed' }}">
                                {{ $consultation->status == 'pending' ? 'نیاز به تأیید' : 'تأیید شده' }}
                            </span>
                            <a href="#" class="action-btn" title="بررسی درخواست"><i class="fas fa-chevron-left"></i></a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-check"></i>
                        <p>هیچ نوبت مشاوره‌ای در حال حاضر ثبت نشده است.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- پرونده‌های فعال --}}
        <div class="panel-box">
            <div class="panel-header">
                <h3><i class="fas fa-folder-open"></i> جدیدترین پرونده‌های فعال</h3>
                <a href="#">همه پرونده‌ها &leftarrow;</a>
            </div>

            <div class="list-wrapper">
                @forelse($recentActiveCases as $case)
                    <div class="list-item">
                        <div class="item-info">
                            <h4>{{ $case->title }}</h4>
                            <p>
                                <i class="fas fa-hashtag"></i> {{ $case->case_number }}
                                | 
                                <i class="fas fa-user-tie"></i> موکل: {{ $case->user->name ?? 'ناشناس' }}
                            </p>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span class="badge bg-active">در جریان</span>
                            <a href="#" class="action-btn" title="مدیریت پرونده"><i class="fas fa-chevron-left"></i></a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>شما در حال حاضر هیچ پرونده فعالی ندارید.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection