@extends('layouts.lawyer')
@section('title', 'داشبورد')

@push('styles')
<style>
    .db-grid { display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:18px; margin-bottom:24px; }

    /* کارت آمار */
    .kpi-card {
        background:#fff; border-radius:14px; padding:20px 22px;
        box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid rgba(0,0,0,0.03);
        display:flex; align-items:center; gap:16px; transition:0.3s; position:relative; overflow:hidden;
    }
    .kpi-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,0.08); }
    .kpi-card::after { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:0 4px 4px 0; }
    .kpi-card.blue::after   { background:#3b82f6; }
    .kpi-card.green::after  { background:#10b981; }
    .kpi-card.amber::after  { background:#f59e0b; }
    .kpi-card.red::after    { background:#ef4444; }

    .kpi-icon {
        width:52px; height:52px; border-radius:12px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; font-size:1.4rem;
    }
    .kpi-card.blue  .kpi-icon { background:#dbeafe; color:#2563eb; }
    .kpi-card.green .kpi-icon { background:#d1fae5; color:#059669; }
    .kpi-card.amber .kpi-icon { background:#fef3c7; color:#d97706; }
    .kpi-card.red   .kpi-icon { background:#fee2e2; color:#dc2626; }

    .kpi-info h3 { font-size:1.7rem; font-weight:900; color:var(--navy); line-height:1; margin-bottom:4px; }
    .kpi-info p { font-size:0.8rem; color:#94a3b8; font-weight:600; margin:0; }
    .kpi-pulse {
        position:absolute; top:12px; left:12px; font-size:0.65rem; font-weight:800;
        background:#ef4444; color:#fff; padding:2px 8px; border-radius:20px;
        animation:blink 1.5s infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.5} }

    /* گرید اصلی */
    .main-grid { display:grid; grid-template-columns:1fr 340px; gap:22px; align-items:start; }

    .panel { background:#fff; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,0.05); overflow:hidden; margin-bottom:20px; }
    .panel-head {
        padding:16px 22px; border-bottom:1px solid #f1f5f9;
        display:flex; justify-content:space-between; align-items:center;
    }
    .panel-head h3 { font-size:0.95rem; font-weight:800; color:var(--navy); display:flex; align-items:center; gap:8px; margin:0; }
    .panel-head h3 i { color:var(--gold-main); }
    .panel-head a { font-size:0.78rem; color:var(--gold-dark); font-weight:700; text-decoration:none; transition:0.2s; }
    .panel-head a:hover { color:var(--gold-main); }
    .panel-body { padding:16px 22px; }

    /* لیست مشاوره‌ها */
    .consult-item {
        display:flex; align-items:center; gap:14px;
        padding:12px 0; border-bottom:1px solid #f8fafc;
    }
    .consult-item:last-child { border-bottom:none; }
    .ci-avatar {
        width:42px; height:42px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg,var(--gold-main),var(--gold-dark));
        display:flex; align-items:center; justify-content:center;
        font-weight:800; font-size:1rem; color:#fff;
    }
    .ci-info { flex:1; min-width:0; }
    .ci-info h4 { font-size:0.88rem; font-weight:700; color:var(--navy); margin:0 0 3px; }
    .ci-info p { font-size:0.75rem; color:#94a3b8; margin:0; }
    .ci-time { font-size:0.75rem; font-weight:700; color:var(--gold-dark); white-space:nowrap; }
    .badge { padding:3px 10px; border-radius:20px; font-size:0.7rem; font-weight:700; }
    .badge-pending  { background:#fef3c7; color:#b45309; }
    .badge-confirmed{ background:#dbeafe; color:#1d4ed8; }

    /* لیست پرونده */
    .case-item {
        display:flex; align-items:center; gap:14px;
        padding:12px 0; border-bottom:1px solid #f8fafc;
    }
    .case-item:last-child { border-bottom:none; }
    .case-dot { width:10px; height:10px; border-radius:50%; background:#10b981; flex-shrink:0; }
    .case-info { flex:1; min-width:0; }
    .case-info h4 { font-size:0.88rem; font-weight:700; color:var(--navy); margin:0 0 3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .case-info p { font-size:0.75rem; color:#94a3b8; margin:0; }

    /* تقویم */
    .calendar-box { background:#fff; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,0.05); overflow:hidden; }
    .cal-header {
        background:linear-gradient(135deg,var(--navy),#1e3a5f);
        padding:18px 20px; color:#fff;
        display:flex; align-items:center; justify-content:space-between;
    }
    .cal-header h3 { font-size:0.95rem; font-weight:800; margin:0; }
    .cal-header span { font-size:0.78rem; color:rgba(255,255,255,0.6); }
    .cal-nav { display:flex; gap:8px; }
    .cal-nav button {
        width:30px; height:30px; border-radius:50%; border:1px solid rgba(255,255,255,0.2);
        background:rgba(255,255,255,0.08); color:#fff; cursor:pointer; font-size:0.8rem;
        display:flex; align-items:center; justify-content:center; transition:0.2s;
    }
    .cal-nav button:hover { background:rgba(255,255,255,0.18); }

    .cal-weekdays {
        display:grid; grid-template-columns:repeat(7,1fr);
        padding:12px 16px 4px;
        background:#fafafa; border-bottom:1px solid #f0f0f0;
    }
    .cal-weekdays span { text-align:center; font-size:0.68rem; font-weight:700; color:#94a3b8; }

    .cal-days {
        display:grid; grid-template-columns:repeat(7,1fr);
        padding:8px 16px 16px; gap:4px;
    }
    .cal-day {
        aspect-ratio:1; display:flex; align-items:center; justify-content:center;
        border-radius:8px; font-size:0.8rem; font-weight:600; color:#64748b;
        cursor:pointer; transition:0.2s; position:relative;
    }
    .cal-day:hover { background:#f1f5f9; }
    .cal-day.today { background:var(--gold-main); color:#fff; font-weight:800; }
    .cal-day.has-event::after {
        content:''; position:absolute; bottom:3px;
        width:4px; height:4px; border-radius:50%; background:#ef4444;
    }
    .cal-day.today.has-event::after { background:#fff; }
    .cal-day.other-month { color:#d1d5db; }
    .cal-day.friday { color:#ef4444; }
    .cal-day.today.friday { background:#ef4444; color:#fff; }

    /* فعالیت‌های امروز */
    .today-list { padding:16px 20px; }
    .today-item {
        display:flex; align-items:center; gap:12px;
        padding:10px 0; border-bottom:1px solid #f8fafc;
    }
    .today-item:last-child { border-bottom:none; }
    .today-time { font-size:0.72rem; font-weight:800; color:var(--gold-dark); min-width:42px; }
    .today-dot { width:8px; height:8px; border-radius:50%; background:var(--gold-main); flex-shrink:0; }
    .today-text { font-size:0.82rem; color:var(--navy); font-weight:600; }

    /* خلاصه مالی */
    .finance-summary { display:grid; grid-template-columns:1fr 1fr; gap:12px; padding:16px 22px; }
    .fs-item { padding:14px; border-radius:10px; text-align:center; }
    .fs-item.green { background:#f0fdf4; }
    .fs-item.blue  { background:#eff6ff; }
    .fs-item .n { font-size:1.1rem; font-weight:900; display:block; }
    .fs-item.green .n { color:#059669; }
    .fs-item.blue  .n { color:#2563eb; }
    .fs-item .l { font-size:0.72rem; color:#94a3b8; margin-top:3px; display:block; }

    @media(max-width:1200px) { .db-grid { grid-template-columns:1fr 1fr; } }
    @media(max-width:960px)  { .main-grid { grid-template-columns:1fr; } }
    @media(max-width:600px)  { .db-grid { grid-template-columns:1fr 1fr; } }
</style>
@endpush

@section('content')

{{-- خوش‌آمدگویی --}}
<div style="background:linear-gradient(135deg,var(--navy),#1e3a5f);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;position:relative;overflow:hidden;">
    <div style="position:absolute;left:-30px;top:-40px;font-size:12rem;color:rgba(212,175,55,0.04);font-family:serif;pointer-events:none;">§</div>
    <div style="position:relative;z-index:1;">
        <h2 style="font-size:1.35rem;font-weight:900;color:var(--gold-main);margin:0 0 6px;">سلام، {{ $lawyer->name }}</h2>
        <p style="color:rgba(255,255,255,0.65);font-size:0.88rem;margin:0;">
            <i class="fas fa-calendar-day" style="margin-left:5px;color:var(--gold-main);"></i>
            {{ \Morilog\Jalali\Jalalian::now()->format('l، d F Y') }}
        </p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;position:relative;z-index:1;">
        <a href="{{ route('lawyer.consultations.index') }}"
           style="background:rgba(212,175,55,0.15);border:1px solid rgba(212,175,55,0.3);color:var(--gold-main);padding:9px 18px;border-radius:10px;font-weight:700;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
            <i class="fas fa-headset"></i> مشاوره‌ها
        </a>
        <a href="{{ route('lawyer.cases.create') }}"
           style="background:linear-gradient(135deg,var(--gold-main),var(--gold-dark));color:#fff;padding:9px 18px;border-radius:10px;font-weight:700;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:7px;box-shadow:0 5px 15px rgba(212,175,55,0.3);">
            <i class="fas fa-folder-plus"></i> پرونده جدید
        </a>
    </div>
</div>

{{-- KPI Cards --}}
<div class="db-grid">
    <div class="kpi-card blue">
        <div class="kpi-icon"><i class="fas fa-briefcase"></i></div>
        <div class="kpi-info">
            <h3>{{ $activeCasesCount }}</h3>
            <p>پرونده فعال</p>
        </div>
    </div>
    <div class="kpi-card green">
        <div class="kpi-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="kpi-info">
            <h3>{{ $todayConsultationsCount }}</h3>
            <p>مشاوره امروز</p>
        </div>
    </div>
    <div class="kpi-card amber">
        @if($pendingRequestsCount > 0)
            <span class="kpi-pulse">جدید</span>
        @endif
        <div class="kpi-icon"><i class="fas fa-hourglass-half"></i></div>
        <div class="kpi-info">
            <h3>{{ $pendingRequestsCount }}</h3>
            <p>درخواست جدید</p>
        </div>
    </div>
    <div class="kpi-card red">
        @if($unreadMessagesCount > 0)
            <span class="kpi-pulse">جدید</span>
        @endif
        <div class="kpi-icon"><i class="fas fa-comment-dots"></i></div>
        <div class="kpi-info">
            <h3>{{ $unreadMessagesCount }}</h3>
            <p>پیام نخوانده</p>
        </div>
    </div>
</div>

<div class="main-grid">

    {{-- ستون چپ --}}
    <div>
        {{-- مشاوره‌های پیش رو --}}
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-headset"></i> مشاوره‌های پیش رو</h3>
                <a href="{{ route('lawyer.consultations.index') }}">همه مشاوره‌ها <i class="fas fa-arrow-left" style="font-size:0.65rem;"></i></a>
            </div>
            <div class="panel-body">
                @forelse($upcomingConsultations as $c)
                    <div class="consult-item">
                        <div class="ci-avatar">{{ mb_substr($c->user->name ?? 'م', 0, 1) }}</div>
                        <div class="ci-info">
                            <h4>{{ $c->user->name ?? 'موکل' }}</h4>
                            <p>
                                <i class="fas fa-tag" style="font-size:0.65rem;margin-left:3px;"></i>
                                @if($c->type==='appointment') حضوری @elseif($c->type==='call') تلفنی @else چت @endif
                                &nbsp;·&nbsp;
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($c->scheduled_at)->format('d F') }}
                            </p>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:5px;">
                            <span class="ci-time">{{ $c->scheduled_at->format('H:i') }}</span>
                            <span class="badge {{ $c->status==='pending' ? 'badge-pending' : 'badge-confirmed' }}">
                                {{ $c->status==='pending' ? 'انتظار' : 'تأیید شده' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:30px;color:#94a3b8;">
                        <i class="fas fa-calendar-check" style="font-size:2rem;display:block;margin-bottom:10px;color:var(--gold-main);opacity:0.4;"></i>
                        <p style="font-size:0.85rem;">مشاوره‌ای در برنامه نیست</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- پرونده‌های اخیر --}}
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-folder-open"></i> پرونده‌های فعال اخیر</h3>
                <a href="{{ route('lawyer.cases.index') }}">همه پرونده‌ها <i class="fas fa-arrow-left" style="font-size:0.65rem;"></i></a>
            </div>
            <div class="panel-body">
                @forelse($recentActiveCases as $case)
                    <div class="case-item">
                        <div class="case-dot"></div>
                        <div class="case-info">
                            <h4>{{ $case->title }}</h4>
                            <p><i class="fas fa-hashtag" style="font-size:0.65rem;"></i> {{ $case->case_number }} &nbsp;·&nbsp; {{ $case->user->name ?? '—' }}</p>
                        </div>
                        <a href="{{ route('lawyer.cases.show', $case) }}"
                           style="padding:5px 12px;background:#f1f5f9;border-radius:7px;font-size:0.75rem;font-weight:700;color:var(--navy);text-decoration:none;transition:0.2s;"
                           onmouseover="this.style.background='var(--navy)';this.style.color='#fff';"
                           onmouseout="this.style.background='#f1f5f9';this.style.color='var(--navy)';">
                            مشاهده
                        </a>
                    </div>
                @empty
                    <div style="text-align:center;padding:30px;color:#94a3b8;">
                        <i class="fas fa-box-open" style="font-size:2rem;display:block;margin-bottom:10px;color:var(--gold-main);opacity:0.4;"></i>
                        <p style="font-size:0.85rem;">پرونده فعالی وجود ندارد</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ستون راست: تقویم --}}
    <div>
        <div class="calendar-box" id="lawyerCalendar">
            <div class="cal-header">
                <div>
                    <h3 id="calMonthTitle">در حال بارگذاری...</h3>
                    <span id="calYearSub"></span>
                </div>
                <div class="cal-nav">
                    <button onclick="changeMonth(-1)" title="ماه قبل"><i class="fas fa-chevron-right"></i></button>
                    <button onclick="changeMonth(1)" title="ماه بعد"><i class="fas fa-chevron-left"></i></button>
                </div>
            </div>
            <div class="cal-weekdays">
                @foreach(['ش','ی','د','س','چ','پ','ج'] as $d)
                    <span>{{ $d }}</span>
                @endforeach
            </div>
            <div class="cal-days" id="calDays">
                <div style="grid-column:1/-1;text-align:center;padding:20px;color:#94a3b8;font-size:0.8rem;">
                    <i class="fas fa-spinner fa-spin"></i> در حال بارگذاری...
                </div>
            </div>

            {{-- مشاوره‌های امروز --}}
            <div style="padding:14px 20px 6px;border-top:1px solid #f1f5f9;">
                <div style="font-size:0.78rem;font-weight:800;color:var(--navy);margin-bottom:10px;">
                    <i class="fas fa-sun" style="color:var(--gold-main);margin-left:5px;"></i>برنامه امروز
                </div>
            </div>
            <div class="today-list" id="todayList">
                @forelse($upcomingConsultations->filter(fn($c) => $c->scheduled_at->isToday()) as $c)
                    <div class="today-item">
                        <span class="today-time">{{ $c->scheduled_at->format('H:i') }}</span>
                        <div class="today-dot"></div>
                        <span class="today-text">{{ $c->user->name ?? 'موکل' }}</span>
                    </div>
                @empty
                    <div style="text-align:center;padding:10px 0 16px;color:#94a3b8;font-size:0.8rem;">
                        برنامه‌ای برای امروز ثبت نشده
                    </div>
                @endforelse
            </div>
        </div>

        {{-- خلاصه مالی --}}
        @php
            $totalFee  = $lawyer->cases()->sum('total_fee');
            $totalPaid = $lawyer->cases()->sum('paid_amount');
        @endphp
        <div class="panel" style="margin-top:18px;">
            <div class="panel-head">
                <h3><i class="fas fa-wallet"></i> خلاصه مالی</h3>
                <a href="{{ route('lawyer.payments.index') }}">جزئیات</a>
            </div>
            <div class="finance-summary">
                <div class="fs-item green">
                    <span class="n">{{ number_format($totalPaid / 1000000, 1) }}M</span>
                    <span class="l">دریافت‌شده (ت)</span>
                </div>
                <div class="fs-item blue">
                    <span class="n">{{ number_format(max(0, $totalFee - $totalPaid) / 1000000, 1) }}M</span>
                    <span class="l">باقی‌مانده (ت)</span>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
// ─── تقویم شمسی ─────────────────────────────────────────────
const jalaliMonths = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
const daysInMonth = [31,31,31,31,31,31,30,30,30,30,30,29];

// تبدیل شمسی به میلادی (تقریبی برای نمایش)
function jalaliToGregorian(jy, jm, jd) {
    jy += 1595;
    let days = -355779 + (365 * jy) + (Math.floor(jy / 33) * 8) + Math.floor(((jy % 33) + 3) / 4) + jd;
    for (let i = 1; i < jm; i++) days += daysInMonth[i - 1];
    const gy = 400 * Math.floor(days / 146097);
    days %= 146097;
    const gd = Math.floor(days / 36524);
    return gy + gd;
}

// تاریخ شمسی امروز از سرور
const todayJalali = @json(\Morilog\Jalali\Jalalian::now()->format('Y/m/d'));
const [todayYear, todayMonth, todayDay] = todayJalali.split('/').map(Number);

let curYear = todayYear;
let curMonth = todayMonth;

// رویدادها از مشاوره‌ها
const events = @json($upcomingConsultations->map(fn($c) => \Morilog\Jalali\Jalalian::fromCarbon($c->scheduled_at)->format('Y/m/d'))->values());
const eventSet = new Set(events);

function renderCalendar(year, month) {
    const title = document.getElementById('calMonthTitle');
    const sub = document.getElementById('calYearSub');
    const container = document.getElementById('calDays');

    title.textContent = jalaliMonths[month - 1];
    sub.textContent = year;

    const totalDays = daysInMonth[month - 1] + (month === 12 && year % 4 === 3 ? 1 : 0);

    // اول ماه چه روزی است؟ (شنبه=0)
    // از morilog/jalali استفاده می‌کنیم — اینجا یک تقریب ساده:
    const firstDayGreg = new Date(year + 621, month - 1, 1 + (month <= 6 ? month * 31 : 186 + (month - 7) * 30));
    let firstDay = (firstDayGreg.getDay() + 1) % 7; // شنبه=0

    let html = '';

    // سلول‌های خالی ابتدا
    for (let i = 0; i < firstDay; i++) {
        html += '<div class="cal-day other-month">—</div>';
    }

    for (let d = 1; d <= totalDays; d++) {
        const dayNum = (firstDay + d - 1) % 7; // 6 = جمعه
        const dateStr = `${year}/${String(month).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
        const isToday = (d === todayDay && month === todayMonth && year === todayYear);
        const hasEvent = eventSet.has(dateStr);
        const isFriday = (dayNum === 6);

        let cls = 'cal-day';
        if (isToday) cls += ' today';
        if (hasEvent) cls += ' has-event';
        if (isFriday) cls += ' friday';

        html += `<div class="${cls}" title="${hasEvent ? 'مشاوره دارید' : ''}">${d}</div>`;
    }

    container.innerHTML = html;
}

function changeMonth(delta) {
    curMonth += delta;
    if (curMonth > 12) { curMonth = 1; curYear++; }
    if (curMonth < 1)  { curMonth = 12; curYear--; }
    renderCalendar(curYear, curMonth);
}

document.addEventListener('DOMContentLoaded', () => renderCalendar(curYear, curMonth));
</script>
@endpush

@endsection