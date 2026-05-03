@extends('layouts.lawyer')
@section('title', 'پروفایل موکل: ' . $client->name)

@push('styles')
    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--gold-dark);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-link:hover {
            color: var(--gold-main);
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 25px;
            align-items: start;
        }

        .profile-sidebar {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .client-card {
            background: linear-gradient(135deg, var(--navy), #1e3a5f);
            border-radius: 14px;
            padding: 28px;
            color: #fff;
            text-align: center;
        }

        .client-avatar-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.2);
            border: 3px solid rgba(212, 175, 55, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 900;
            color: var(--gold-main);
            margin: 0 auto 14px;
        }

        .client-card h3 {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .client-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.82rem;
        }

        .badge-special-wt {
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: var(--gold-main);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
        }

        .info-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f5f0ea;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i {
            color: var(--gold-main);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 0.88rem;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #888;
        }

        .info-value {
            font-weight: 700;
            color: var(--navy);
        }

        .mini-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .ms-item {
            text-align: center;
            background: #f8fafc;
            padding: 14px;
            border-radius: 10px;
        }

        .ms-item .n {
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--navy);
            display: block;
        }

        .ms-item .l {
            font-size: 0.72rem;
            color: #888;
        }

        .upgrade-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.2s;
            margin-top: 14px;
        }

        .upgrade-btn:hover {
            opacity: 0.9;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--gold-main);
        }

        .consult-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .consult-item {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            border: 1px solid #f0f0f0;
            transition: 0.2s;
        }

        .consult-item:hover {
            border-color: var(--gold-main);
        }

        .ci-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ci-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .ci-info h4 {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0 0 3px;
        }

        .ci-info p {
            font-size: 0.75rem;
            color: #888;
            margin: 0;
        }

        .case-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .case-item {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #f0f0f0;
            border-right: 3px solid var(--gold-main);
            transition: 0.2s;
        }

        .case-item:hover {
            transform: translateY(-2px);
        }

        .case-item .num {
            font-size: 0.72rem;
            color: var(--gold-dark);
            font-weight: 700;
            margin-bottom: 4px;
        }

        .case-item h4 {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .case-item .meta {
            font-size: 0.75rem;
            color: #888;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-closed {
            background: #f1f5f9;
            color: #64748b;
        }

        .badge-won {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-lost {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-pending {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-confirmed {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-sm {
            padding: 7px 14px;
            background: var(--navy);
            color: #fff;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;
            transition: 0.2s;
        }

        .btn-sm:hover {
            background: var(--gold-main);
            color: var(--navy);
        }

        .empty-msg {
            text-align: center;
            padding: 30px;
            color: #aaa;
            font-size: 0.85rem;
            background: #fff;
            border-radius: 12px;
        }

        .empty-msg i {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
            opacity: 0.3;
        }

        @media(max-width:960px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    <a href="{{ route('lawyer.clients.index') }}" class="back-link">
        <i class="fas fa-arrow-right"></i> بازگشت به موکلین
    </a>

    <div class="profile-grid">

        {{-- سایدبار --}}
        <div class="profile-sidebar">
            <div class="client-card">
                <div class="client-avatar-lg">{{ mb_substr($client->name, 0, 1) }}</div>
                <h3>{{ $client->name }}</h3>
                <p>{{ $client->phone }}</p>
                @if ($client->email)
                    <p>{{ $client->email }}</p>
                @endif
                @if ($client->isSpecial())
                    <div class="badge-special-wt">
                        <i class="fas fa-crown" style="font-size:0.7rem;"></i> موکل ویژه
                    </div>
                @endif
            </div>

            <div class="info-card">
                <div class="card-title"><i class="fas fa-id-card"></i> اطلاعات هویتی</div>
                <div class="info-row">
                    <span class="info-label">کد ملی</span>
                    <span class="info-value">{{ $client->national_code ?? 'ثبت نشده' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">وضعیت</span>
                    <span class="info-value">{{ $client->status === 'active' ? 'فعال' : 'مسدود' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">تاریخ عضویت</span>
                    <span

                        class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($client->created_at)->format('Y/m/d') }}</span>
                </div>
                @if ($client->upgraded_at)
                    <div class="info-row">
                        <span class="info-label">ارتقا به ویژه</span>
                        @dd($client->upgraded_at);
                        <span
                            class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($client->upgraded_at)->format('Y/m/d') }}</span>
                    </div>
                @endif


                <div class="mini-stats" style="margin-top:16px;">
                    <div class="ms-item">
                        <span class="n">{{ $cases->count() }}</span>
                        <span class="l">پرونده</span>
                    </div>
                    <div class="ms-item">
                        <span class="n">{{ $consultations->count() }}</span>
                        <span class="l">مشاوره</span>
                    </div>
                    <div class="ms-item">
                        @php $totalPaid = $cases->sum('paid_amount'); @endphp
                        <span class="n">{{ number_format($totalPaid / 1000000, 0) }}M</span>
                        <span class="l">پرداختی (ت)</span>
                    </div>
                </div>

                @if ($client->isSimple())
                    <form method="POST" action="{{ route('lawyer.clients.upgrade', $client) }}">
                        @csrf
                        <button type="submit" class="upgrade-btn">
                            <i class="fas fa-crown"></i> ارتقا به موکل ویژه
                        </button>
                    </form>
                @endif
            </div>

            @if ($client->conversations()->where('lawyer_id', auth('lawyer')->id())->exists())
                <div class="info-card">
                    <div class="card-title"><i class="fas fa-comments"></i> گفتگوها</div>
                    @foreach ($client->conversations()->where('lawyer_id', auth('lawyer')->id())->get() as $conv)
                        <a href="{{ route('lawyer.chat.show', $conv->id) }}"
                            style="display:block;padding:10px 14px;background:#f8fafc;border-radius:8px;font-size:0.82rem;color:var(--navy);font-weight:700;text-decoration:none;margin-bottom:8px;transition:0.2s;"
                            onmouseover="this.style.background='var(--navy)';this.style.color='#fff';"
                            onmouseout="this.style.background='#f8fafc';this.style.color='var(--navy)';">
                            <i class="fas fa-comment-dots" style="color:var(--gold-main);margin-left:6px;"></i>
                            {{ Str::limit($conv->title ?? 'گفتگو', 30) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- محتوای اصلی --}}
        <div>
            {{-- پرونده‌ها --}}
            <div class="section-title">
                <i class="fas fa-briefcase"></i> پرونده‌های حقوقی
            </div>

            @if ($cases->isNotEmpty())
                <div class="case-list" style="margin-bottom:30px;">
                    @foreach ($cases as $case)
                        @php
                            $statusMap = [
                                'active' => ['l' => 'فعال', 'c' => 'badge-active'],
                                'on_hold' => ['l' => 'معلق', 'c' => 'badge-pending'],
                                'closed' => ['l' => 'بسته', 'c' => 'badge-closed'],
                                'won' => ['l' => 'برنده', 'c' => 'badge-won'],
                                'lost' => ['l' => 'بازنده', 'c' => 'badge-lost'],
                            ];
                            $s = $statusMap[$case->current_status] ?? ['l' => $case->current_status, 'c' => ''];
                        @endphp
                        <div class="case-item">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                                <div>
                                    <div class="num"># {{ $case->case_number }}</div>
                                    <h4>{{ $case->title }}</h4>
                                    <div class="meta">
                                        <span><i class="fas fa-calendar-alt"></i>
                                            @dd($case->opened_at);
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($case->opened_at ?? $case->created_at)->format('Y/m/d') }}</span>
                                        <span><i class="fas fa-money-bill-wave"></i> حق‌الوکاله:
                                            {{ number_format($case->total_fee) }} ت</span>
                                        <span><i class="fas fa-tasks"></i> پرداخت: {{ $case->progress_percent }}٪</span>
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                                    <span class="badge {{ $s['c'] }}">{{ $s['l'] }}</span>
                                    <a href="{{ route('lawyer.cases.show', $case) }}" class="btn-sm">
                                        <i class="fas fa-eye"></i> جزئیات
                                    </a>
                                </div>
                            </div>
                            @if ($case->statusLogs->first())
                                <div
                                    style="margin-top:10px;padding-top:10px;border-top:1px solid #f0f0f0;font-size:0.78rem;color:#888;">
                                    <i class="fas fa-history" style="color:var(--gold-main);margin-left:5px;"></i>
                                    آخرین وضعیت: {{ $case->statusLogs->first()->status_title }}
                                    @if ($case->statusLogs->first()->status_date)
                                        {{ \Morilog\Jalali\Jalalian::fromCarbon($case->statusLogs->first()->status_date)->format('Y/m/d') }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div style="text-align:left;margin-bottom:30px;">
                    <a href="{{ route('lawyer.cases.create') }}?user_id={{ $client->id }}"
                        style="padding:10px 20px;background:var(--navy);color:#fff;border-radius:9px;font-size:0.85rem;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
                        <i class="fas fa-folder-plus"></i> ایجاد پرونده جدید
                    </a>
                </div>
            @else
                <div class="empty-msg" style="margin-bottom:30px;">
                    <i class="fas fa-folder-open"></i>
                    <p>هیچ پرونده‌ای ثبت نشده</p>
                    <a href="{{ route('lawyer.cases.create') }}"
                        style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:var(--navy);color:#fff;border-radius:9px;font-size:0.85rem;font-weight:700;text-decoration:none;margin-top:12px;">
                        <i class="fas fa-plus"></i> ایجاد پرونده
                    </a>
                </div>
            @endif

            {{-- مشاوره‌ها --}}
            <div class="section-title">
                <i class="fas fa-headset"></i> مشاوره‌ها
            </div>

            @if ($consultations->isNotEmpty())
                <div class="consult-list">
                    @foreach ($consultations as $c)
                        @php
                            $statusMap2 = [
                                'pending' => ['l' => 'در انتظار', 'c' => 'badge-pending'],
                                'confirmed' => ['l' => 'تأیید شده', 'c' => 'badge-confirmed'],
                                'completed' => ['l' => 'تکمیل شده', 'c' => 'badge-completed'],
                                'cancelled' => ['l' => 'لغو شده', 'c' => 'badge-cancelled'],
                                'rejected' => ['l' => 'رد شده', 'c' => 'badge-cancelled'],
                            ];
                            $s2 = $statusMap2[$c->status] ?? ['l' => $c->status, 'c' => ''];
                            $typeIcons = [
                                'appointment' => 'fa-calendar-check',
                                'call' => 'fa-phone',
                                'chat' => 'fa-comment',
                            ];
                        @endphp
                        <div class="consult-item">
                            <div class="ci-left">
                                <div class="ci-icon" style="background:#f1f5f9;color:var(--navy);">
                                    <i class="fas {{ $typeIcons[$c->type] ?? 'fa-file' }}"></i>
                                </div>
                                <div class="ci-info">
                                    <h4>{{ $c->title }}</h4>
                                    <p>
                                        @if ($c->scheduled_at)
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($c->scheduled_at)->format('Y/m/d') }}
                                            ساعت {{ $c->scheduled_at->format('H:i') }}
                                        @else
                                            {{ $c->created_at->diffForHumans() }}
                                        @endif
                                        · {{ number_format($c->price) }} ت
                                    </p>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="badge {{ $s2['c'] }}">{{ $s2['l'] }}</span>
                                <a href="{{ route('lawyer.consultations.show', $c) }}" class="btn-sm">جزئیات</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-msg">
                    <i class="fas fa-headset"></i>
                    <p>هیچ مشاوره‌ای ثبت نشده</p>
                </div>
            @endif
        </div>
    </div>

@endsection
