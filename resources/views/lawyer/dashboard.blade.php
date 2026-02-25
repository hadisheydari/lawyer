@extends('layouts.lawyer')

@section('title', 'داشبورد')

@push('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: var(--bg-white); padding: 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border-right: 4px solid transparent;
            display: flex; align-items: center; gap: 18px;
            transition: var(--transition);
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-card.blue  { border-color: #3b82f6; }
        .stat-card.gold  { border-color: var(--gold-main); }
        .stat-card.green { border-color: var(--success); }
        .stat-card.red   { border-color: var(--danger); }
        .stat-icon {
            width: 50px; height: 50px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-info h3 { margin: 0; font-size: 1.8rem; font-weight: 800; color: var(--text-heading); }
        .stat-info span { font-size: 0.82rem; color: #6b7280; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .table-responsive { width: 100%; overflow-x: auto; }
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th {
            text-align: right; padding: 14px 16px;
            color: #6b7280; font-size: 0.82rem; font-weight: 600;
            border-bottom: 1px solid #f3f4f6;
        }
        .custom-table td {
            padding: 14px 16px; border-bottom: 1px solid #f3f4f6;
            color: var(--text-heading); font-size: 0.88rem;
        }
        .custom-table tr:last-child td { border-bottom: none; }
        .custom-table tr:hover td { background: #fafafa; }

        .client-cell { display: flex; align-items: center; gap: 10px; }
        .client-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--navy); color: var(--gold-main);
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 0.85rem; flex-shrink: 0;
        }
        .status-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; }
        .status-open    { background: #ecfdf5; color: var(--success); }
        .status-pending { background: #fffbeb; color: var(--warning); }
        .status-closed  { background: #f3f4f6; color: #6b7280; }

        .schedule-list { display: flex; flex-direction: column; gap: 14px; }
        .schedule-item { display: flex; gap: 14px; padding-bottom: 14px; border-bottom: 1px solid #f3f4f6; }
        .schedule-item:last-child { border-bottom: none; padding-bottom: 0; }
        .time-box {
            background: #f3f4f6; color: var(--navy); padding: 6px 10px;
            border-radius: 8px; text-align: center; font-weight: 700; font-size: 0.82rem;
            min-width: 55px; display: flex; flex-direction: column; justify-content: center;
            flex-shrink: 0;
        }
        .schedule-info h4 { margin: 0 0 4px; font-size: 0.9rem; color: var(--text-heading); font-weight: 700; }
        .schedule-info p { margin: 0; font-size: 0.78rem; color: #6b7280; display: flex; align-items: center; gap: 5px; }

        .pending-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px; border: 1px solid #eee; border-radius: 8px; margin-bottom: 10px;
            transition: 0.2s;
        }
        .pending-item:hover { border-color: var(--gold-light); }
        .p-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: #e0f2fe; color: #3b82f6;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 0.9rem; flex-shrink: 0;
        }
        .p-info h4 { margin: 0; font-size: 0.88rem; color: var(--text-heading); font-weight: 700; }
        .p-info p { margin: 3px 0 0; font-size: 0.75rem; color: #888; }
        .p-actions { margin-right: auto; display: flex; gap: 6px; }
        .btn-xs {
            padding: 4px 10px; border-radius: 4px; font-size: 0.75rem;
            cursor: pointer; font-family: 'Vazirmatn', sans-serif;
            font-weight: 600; border: none; transition: 0.2s;
        }
        .btn-confirm { background: #ecfdf5; color: #065f46; }
        .btn-confirm:hover { background: var(--success); color: #fff; }
        .btn-reject { background: #fef2f2; color: #991b1b; }
        .btn-reject:hover { background: var(--danger); color: #fff; }

        @media (max-width: 1024px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h2>داشبورد مدیریتی</h2>
            <p>خوش آمدید {{ $lawyer->name }} — امروز {{ \Morilog\Jalali\Jalalian::now()->format('l d F Y') }}</p>
        </div>
        <a href="{{ route('lawyer.cases.create') }}"
           style="background:var(--navy);color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:8px;font-weight:600;font-size:0.9rem;text-decoration:none;transition:0.3s;"
           onmouseover="this.style.background='var(--gold-main)';this.style.color='var(--navy)'"
           onmouseout="this.style.background='var(--navy)';this.style.color='#fff'">
            <i class="fas fa-plus"></i> پرونده جدید
        </a>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon" style="background:#e0f2fe;color:#3b82f6;">
                <i class="fas fa-folder"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['active_cases'] }}</h3>
                <span>پرونده‌های فعال</span>
            </div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon" style="background:#d1fae5;color:var(--success);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['won_cases'] }}</h3>
                <span>پرونده موفق</span>
            </div>
        </div>
        <div class="stat-card gold">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;">
                <i class="far fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['pending_consult'] }}</h3>
                <span>مشاوره در انتظار</span>
            </div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon" style="background:#fee2e2;color:var(--danger);">
                <i class="far fa-comment-dots"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['unread_messages'] }}</h3>
                <span>گفتگوی فعال</span>
            </div>
        </div>
    </div>

    {{-- Dashboard Grid --}}
    <div class="dashboard-grid">

        {{-- جدول پرونده‌های اخیر --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">آخرین پرونده‌ها</span>
                <a href="{{ route('lawyer.cases.index') }}" class="card-action">مشاهده همه</a>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                    <tr>
                        <th>شماره</th>
                        <th>موکل</th>
                        <th>موضوع</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($activeCases as $case)
                        <tr>
                            <td>
                                <a href="{{ route('lawyer.cases.show', $case->id) }}"
                                   style="color:var(--navy);font-weight:700;text-decoration:none;">
                                    #{{ $case->case_number }}
                                </a>
                            </td>
                            <td>
                                <div class="client-cell">
                                    <div class="client-avatar">
                                        {{ mb_substr($case->user->name ?? 'م', 0, 1) }}
                                    </div>
                                    <span>{{ $case->user->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td>{{ $case->title }}</td>
                            <td>
                                <span class="status-badge {{ match($case->current_status) {
                                    'active'  => 'status-open',
                                    'on_hold' => 'status-pending',
                                    default   => 'status-closed'
                                } }}">
                                    {{ match($case->current_status) {
                                        'active'  => 'در جریان',
                                        'on_hold' => 'متوقف',
                                        'won'     => 'موفق',
                                        'lost'    => 'ناموفق',
                                        default   => 'مختومه',
                                    } }}
                                </span>
                            </td>
                            <td>
                                {{ \Morilog\Jalali\Jalalian::fromDateTime($case->opened_at)->format('d M') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:30px;color:#aaa;">
                                هیچ پرونده فعالی وجود ندارد
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ستون راست --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- مشاوره‌های در انتظار --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">مشاوره‌های در انتظار</span>
                    <a href="{{ route('lawyer.consultations.index') }}" class="card-action">همه</a>
                </div>
                @forelse($pendingConsultations as $consult)
                    <div class="pending-item">
                        <div class="p-avatar">
                            {{ mb_substr($consult->user->name ?? 'م', 0, 1) }}
                        </div>
                        <div class="p-info">
                            <h4>{{ $consult->user->name ?? '—' }}</h4>
                            <p>
                                <i class="fas fa-{{ $consult->type === 'chat' ? 'comment' : ($consult->type === 'call' ? 'phone' : 'building') }}"></i>
                                {{ match($consult->type) { 'chat' => 'چت', 'call' => 'تلفنی', default => 'حضوری' } }}
                                • {{ number_format($consult->price) }} تومان
                            </p>
                        </div>
                        <div class="p-actions">
                            <form method="POST" action="{{ route('lawyer.consultations.confirm', $consult->id) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-xs btn-confirm" title="تأیید">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('lawyer.consultations.reject', $consult->id) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-xs btn-reject" title="رد">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:20px;color:#aaa;font-size:0.85rem;">
                        <i class="fas fa-check-circle" style="color:var(--success);font-size:1.5rem;display:block;margin-bottom:8px;"></i>
                        هیچ مشاوره‌ای در انتظار نیست
                    </div>
                @endforelse
            </div>

            {{-- برنامه امروز (ساده) --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">برنامه امروز</span>
                    <a href="{{ route('lawyer.calendar') }}" class="card-action">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="schedule-list">
                    <div style="text-align:center;padding:15px;color:#aaa;font-size:0.85rem;">
                        <i class="far fa-calendar-alt" style="font-size:1.8rem;display:block;margin-bottom:8px;color:var(--gold-main);"></i>
                        <a href="{{ route('lawyer.calendar') }}" style="color:var(--gold-dark);font-weight:600;text-decoration:none;">
                            مشاهده تقویم کامل
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
