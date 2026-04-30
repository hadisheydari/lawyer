@extends('layouts.client')

@section('title', 'مشاوره‌های من')

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--navy);
            margin: 0;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 7px 18px;
            border-radius: 20px;
            border: 1.5px solid #e0e0e0;
            background: #fff;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #888;
            cursor: pointer;
            text-decoration: none;
            transition: 0.2s;
        }

        .filter-tab:hover,
        .filter-tab.active {
            border-color: var(--navy);
            background: var(--navy);
            color: #fff;
        }

        .consult-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .consult-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            transition: 0.3s;
        }

        .consult-card:hover {
            border-color: var(--gold-main);
            transform: translateY(-2px);
        }

        .cc-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            min-width: 0;
        }

        .cc-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .cc-info h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0 0 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cc-meta {
            font-size: 0.8rem;
            color: #888;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .cc-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .cc-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .badge-pending {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-confirmed {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-in_progress {
            background: #ede9fe;
            color: #6d28d9;
        }

        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-sm {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--navy);
            color: #fff;
        }

        .btn-sm:hover {
            background: var(--gold-main);
            color: #fff;
        }

        .empty-box {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .empty-box i {
            font-size: 3rem;
            color: var(--gold-light);
            display: block;
            margin-bottom: 15px;
        }

        .empty-box p {
            color: #aaa;
            margin-bottom: 20px;
        }

        .btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .stats-mini {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-mini-card {
            background: #fff;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
            text-align: center;
        }

        .stat-mini-card .n {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--navy);
            display: block;
        }

        .stat-mini-card .l {
            font-size: 0.8rem;
            color: #888;
            margin-top: 3px;
            display: block;
        }

        @media (max-width: 600px) {
            .consult-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .cc-right {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <h2><i class="fas fa-comments" style="color:var(--gold-main);margin-left:10px;"></i>مشاوره‌های من</h2>
        <a href="{{ route('reserve.index') }}" class="btn-gold"
            style="padding:10px 20px;border-radius:10px;font-size:0.88rem;">
            <i class="fas fa-plus"></i> رزرو نوبت جدید
        </a>
    </div>

    {{-- Stats --}}
    <div class="stats-mini">
        <div class="stat-mini-card">
            <span class="n">{{ $activeCount }}</span>
            <span class="l">فعال</span>
        </div>
        <div class="stat-mini-card">
            <span class="n">{{ $completedCount }}</span>
            <span class="l">تکمیل‌شده</span>
        </div>
        <div class="stat-mini-card">
            <span class="n">{{ $consultations->total() }}</span>
            <span class="l">کل مشاوره‌ها</span>
        </div>
    </div>

    {{-- List --}}
    <div class="consult-list">
        @forelse($consultations as $consultation)
            @php
                $typeMap = [
                    'chat' => ['icon' => 'fas fa-comment', 'bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'چت'],
                    'call' => ['icon' => 'fas fa-phone', 'bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'تماس'],
                    'appointment' => [
                        'icon' => 'fas fa-calendar-check',
                        'bg' => '#ede9fe',
                        'color' => '#6d28d9',
                        'label' => 'حضوری',
                    ],
                ];
                $t = $typeMap[$consultation->type] ?? [
                    'icon' => 'fas fa-file',
                    'bg' => '#f3f4f6',
                    'color' => '#6b7280',
                    'label' => '',
                ];

                $statusMap = [
                    'pending' => ['label' => 'در انتظار تأیید', 'class' => 'badge-pending'],
                    'confirmed' => ['label' => 'تأیید شده', 'class' => 'badge-confirmed'],
                    'in_progress' => ['label' => 'در حال انجام', 'class' => 'badge-in_progress'],
                    'completed' => ['label' => 'تکمیل شده', 'class' => 'badge-completed'],
                    'cancelled' => ['label' => 'لغو شده', 'class' => 'badge-cancelled'],
                    'rejected' => ['label' => 'رد شده', 'class' => 'badge-rejected'],
                ];
                $s = $statusMap[$consultation->status] ?? ['label' => $consultation->status, 'class' => ''];
            @endphp
            <div class="consult-card">
                <div class="cc-left">
                    <div class="cc-icon" style="background:{{ $t['bg'] }};color:{{ $t['color'] }};">
                        <i class="{{ $t['icon'] }}"></i>
                    </div>
                    <div class="cc-info">
                        <h4>{{ $consultation->title }}</h4>
                        <div class="cc-meta">
                            <span><i class="fas fa-user-tie"></i> {{ $consultation->lawyer->name ?? '—' }}</span>
                            <span><i class="fas fa-tag"></i> {{ $t['label'] }}</span>
                            @if ($consultation->scheduled_at)
                                <span>
                                    <i class="far fa-calendar-alt"></i>
                                    @if ($consultation->scheduled_at->year > 1900)
                                        {{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->scheduled_at)->format('Y/m/d') }}
                                    @else
                                        {{ $consultation->scheduled_at->format('Y/m/d') }} (نیاز به اصلاح)
                                    @endif ساعت {{ $consultation->scheduled_at->format('H:i') }}
                                </span>
                            @endif
                            <span><i class="far fa-clock"></i> {{ $consultation->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <div class="cc-right">
                    <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                    <a href="{{ route('client.consultations.show', $consultation->id) }}" class="btn-sm">
                        جزئیات <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-box">
                <i class="fas fa-comments"></i>
                <p>هنوز هیچ مشاوره‌ای ثبت نشده است</p>
                <a href="{{ route('reserve.index') }}" class="btn-gold">
                    <i class="fas fa-calendar-plus"></i> اولین نوبت را رزرو کنید
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($consultations->hasPages())
        <div style="display:flex;justify-content:center;gap:8px;margin-top:30px;flex-wrap:wrap;">
            @if ($consultations->onFirstPage())
                <span style="padding:8px 14px;border-radius:8px;border:1px solid #eee;color:#ccc;">قبلی</span>
            @else
                <a href="{{ $consultations->previousPageUrl() }}"
                    style="padding:8px 14px;border-radius:8px;border:1px solid #ddd;color:var(--navy);text-decoration:none;">قبلی</a>
            @endif

            @foreach ($consultations->getUrlRange(1, $consultations->lastPage()) as $page => $url)
                @if ($page == $consultations->currentPage())
                    <span
                        style="padding:8px 14px;border-radius:8px;background:var(--navy);color:#fff;border:1px solid var(--navy);">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                        style="padding:8px 14px;border-radius:8px;border:1px solid #ddd;color:var(--navy);text-decoration:none;">{{ $page }}</a>
                @endif
            @endforeach

            @if ($consultations->hasMorePages())
                <a href="{{ $consultations->nextPageUrl() }}"
                    style="padding:8px 14px;border-radius:8px;border:1px solid #ddd;color:var(--navy);text-decoration:none;">بعدی</a>
            @else
                <span style="padding:8px 14px;border-radius:8px;border:1px solid #eee;color:#ccc;">بعدی</span>
            @endif
        </div>
    @endif

@endsection
