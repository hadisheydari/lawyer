@extends('layouts.client')

@section('title', 'داشبورد')

@push('styles')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, var(--navy) 0%, #1a2639 100%);
            color: #fff;
            padding: 35px 40px;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .welcome-card::after {
            content: ''; position: absolute; right: -20px; top: -50%;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(197,160,89,0.15) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .welcome-text h2 { font-size: 1.6rem; margin: 0 0 8px; }
        .welcome-text p { opacity: 0.8; margin: 0 0 10px; font-size: 0.9rem; }
        .vip-tag {
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
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

        .content-split { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }

        .section-box {
            background: #fff; padding: 25px; border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .section-title {
            font-size: 1.05rem; font-weight: 800; color: var(--navy);
            margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;
        }
        .view-all { font-size: 0.82rem; color: var(--gold-dark); text-decoration: none; }

        .case-card { border: 1px solid #eee; border-radius: 8px; padding: 18px; margin-bottom: 15px; }
        .case-header { display: flex; justify-content: space-between; margin-bottom: 12px; }
        .case-id { font-weight: 700; color: var(--navy); font-size: 0.9rem; }
        .case-badge { padding: 2px 10px; border-radius: 4px; font-size: 0.78rem; font-weight: 600; }
        .badge-active { background: #e8f8f5; color: #27ae60; }
        .badge-pending { background: #fcf3cf; color: #f39c12; }
        .badge-closed  { background: #fef2f2; color: #e74c3c; }

        .progress-container { margin: 12px 0; }
        .progress-labels { display: flex; justify-content: space-between; font-size: 0.78rem; color: #888; margin-bottom: 5px; }
        .progress-bar-bg { width: 100%; height: 7px; background: #eee; border-radius: 4px; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: linear-gradient(90deg, var(--gold-main), var(--gold-dark)); border-radius: 4px; transition: width 1s ease; }

        .case-lawyer {
            display: flex; align-items: center; gap: 10px;
            margin-top: 12px; border-top: 1px solid #f5f5f5; padding-top: 12px;
            font-size: 0.82rem; color: #666;
        }
        .lawyer-mini-img {
            width: 34px; height: 34px; border-radius: 50%;
            object-fit: cover; border: 1px solid var(--gold-main);
            background: #eee; display: flex; align-items: center;
            justify-content: center; color: var(--navy); font-weight: bold; font-size: 0.85rem;
        }

        .appointment-item {
            display: flex; gap: 14px; align-items: center;
            padding: 12px; border-radius: 8px;
            background: #fdfbf7; border-right: 3px solid var(--gold-main);
            margin-bottom: 10px;
        }
        .date-box {
            text-align: center; background: #fff; padding: 5px 10px;
            border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            color: var(--navy); font-weight: bold; min-width: 48px;
        }
        .date-day { font-size: 1.1rem; display: block; line-height: 1; }
        .date-month { font-size: 0.7rem; color: #888; }
        .appt-info h4 { margin: 0 0 3px; font-size: 0.9rem; color: var(--navy); }
        .appt-info span { font-size: 0.78rem; color: #888; }

        .doc-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px solid #eee; font-size: 0.88rem;
        }
        .doc-item:last-child { border-bottom: none; }

        .fin-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 0.9rem; border-bottom: 1px solid #f5f5f5; }
        .fin-row:last-child { border-bottom: none; }
        .fin-label { color: #888; }
        .fin-value { font-weight: 700; color: var(--navy); }
        .fin-value.danger { color: #e74c3c; }
        .fin-value.success { color: #27ae60; }

        @media (max-width: 900px) {
            .content-split { grid-template-columns: 1fr; }
            .welcome-card { flex-direction: column; text-align: center; gap: 18px; }
            .quick-btns { flex-wrap: wrap; justify-content: center; }
        }
    </style>
@endpush

@section('content')

    {{-- Welcome Card --}}
    <div class="welcome-card">
        <div class="welcome-text">
            <h2>خوش آمدید، {{ auth()->user()->name }}</h2>
            <p>پنل مدیریت پرونده‌های حقوقی شما</p>
            <span class="vip-tag"><i class="fas fa-crown"></i> موکل ویژه</span>
        </div>
        <div class="quick-btns">
            <a href="{{ route('reserve.index') }}" class="q-btn">
                <i class="fas fa-calendar-plus"></i> رزرو وقت
            </a>
            <a href="{{ route('client.chat.index') }}" class="q-btn">
                <i class="fas fa-comment-dots"></i> پیام به وکیل
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;color:#3498db;">
                <i class="fas fa-folder-open"></i>
            </div>
            <span class="stat-value">{{ $activeCases }}</span>
            <span class="stat-label">پرونده‌های جاری</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#eafaf1;color:#2ecc71;">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="stat-value">{{ $closedCases }}</span>
            <span class="stat-label">پرونده‌های تمام‌شده</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef5e7;color:#f39c12;">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <span class="stat-value">{{ $pendingInstallments->count() }}</span>
            <span class="stat-label">قسط در انتظار</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdedec;color:#e74c3c;">
                <i class="far fa-envelope"></i>
            </div>
            <span class="stat-value">{{ $unreadMessages }}</span>
            <span class="stat-label">پیام خوانده‌نشده</span>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="content-split">

        {{-- ستون چپ: پرونده‌ها --}}
        <div>
            <div class="section-box">
                <div class="section-title">
                    آخرین وضعیت پرونده‌ها
                    <a href="{{ route('client.cases.index') }}" class="view-all">
                        مشاهده همه <i class="fas fa-arrow-left"></i>
                    </a>
                </div>

                @forelse($cases as $case)
                    <div class="case-card">
                        <div class="case-header">
                            <span class="case-id"># {{ $case->case_number }}</span>
                            <span class="case-badge
                            @if($case->current_status === 'active') badge-active
                            @elseif($case->current_status === 'on_hold') badge-pending
                            @else badge-closed @endif">
                            {{ match($case->current_status) {
                                'active'  => 'در جریان',
                                'on_hold' => 'متوقف',
                                'won'     => 'موفق',
                                'lost'    => 'ناموفق',
                                default   => 'مختومه',
                            } }}
                        </span>
                        </div>

                        <div style="font-weight:700;font-size:0.95rem;margin-bottom:4px;">
                            {{ $case->title }}
                        </div>

                        @if($case->statusLogs->first())
                            <div style="font-size:0.82rem;color:#888;">
                                آخرین وضعیت: {{ $case->statusLogs->first()->status_title }}
                            </div>
                        @endif

                        <div class="progress-container">
                            <div class="progress-labels">
                                <span>پیشرفت پرداخت</span>
                                <span>{{ $case->progress_percent }}٪</span>
                            </div>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width:{{ $case->progress_percent }}%;"></div>
                            </div>
                        </div>

                        <div class="case-lawyer">
                            <div class="lawyer-mini-img">
                                {{ mb_substr($case->lawyer->name ?? 'و', 0, 1) }}
                            </div>
                            <span>وکیل مسئول: {{ $case->lawyer->name ?? '—' }}</span>
                            <a href="{{ route('client.cases.show', $case->id) }}"
                               style="margin-right:auto;font-size:0.8rem;color:var(--gold-dark);text-decoration:none;">
                                جزئیات <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:40px;color:#aaa;">
                        <i class="fas fa-folder-open" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                        پرونده‌ای ثبت نشده است
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ستون راست: اقساط + مالی --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- اقساط پیش‌رو --}}
            <div class="section-box">
                <div class="section-title">
                    اقساط پیش‌رو
                    <a href="{{ route('client.installments.index') }}" class="view-all">همه</a>
                </div>
                @forelse($pendingInstallments as $inst)
                    <div class="appointment-item"
                         style="border-right-color:{{ $inst->isOverdue() ? '#e74c3c' : 'var(--gold-main)' }}">
                        <div class="date-box">
                        <span class="date-day">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($inst->due_date)->format('d') }}
                        </span>
                            <span class="date-month">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($inst->due_date)->format('M') }}
                        </span>
                        </div>
                        <div class="appt-info">
                            <h4>قسط {{ $inst->installment_number }}م — {{ number_format($inst->amount) }} تومان</h4>
                            <span>{{ $inst->case->title ?? '—' }}
                                @if($inst->isOverdue())
                                    <span style="color:#e74c3c;font-weight:600;"> (معوق)</span>
                                @endif
                        </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:20px;color:#aaa;font-size:0.88rem;">
                        قسط معوقی ندارید 🎉
                    </div>
                @endforelse
            </div>

            {{-- خلاصه مالی --}}
            <div class="section-box">
                <div class="section-title">خلاصه مالی</div>
                <div class="fin-row">
                    <span class="fin-label">کل حق‌الوکاله</span>
                    <span class="fin-value">{{ number_format($totalFee) }} تومان</span>
                </div>
                <div class="fin-row">
                    <span class="fin-label">پرداخت شده</span>
                    <span class="fin-value success">{{ number_format($totalPaid) }} تومان</span>
                </div>
                <div class="fin-row">
                    <span class="fin-label">مانده بدهی</span>
                    <span class="fin-value {{ $totalRemain > 0 ? 'danger' : 'success' }}">
                    {{ number_format($totalRemain) }} تومان
                </span>
                </div>

                @if($totalFee > 0)
                    <div style="margin-top:12px;">
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill"
                                 style="width:{{ min(100, round($totalPaid/$totalFee*100)) }}%;">
                            </div>
                        </div>
                        <div style="text-align:center;font-size:0.78rem;color:#888;margin-top:5px;">
                            {{ round($totalPaid/$totalFee*100) }}٪ پرداخت شده
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection
