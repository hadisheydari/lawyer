@extends('layouts.lawyer')
@section('title', 'تقویم کاری')

@push('styles')
<style>
    .cal-page { display:grid; grid-template-columns:1fr 340px; gap:22px; align-items:start; }

    /* ── تقویم اصلی ── */
    .calendar-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06); }
    .cal-nav-bar {
        background:linear-gradient(135deg,var(--navy),#1e3a5f);
        padding:20px 24px; color:#fff;
        display:flex; justify-content:space-between; align-items:center;
    }
    .cal-nav-bar h2 { font-size:1.2rem; font-weight:900; margin:0; }
    .cal-nav-bar span { font-size:0.82rem; color:rgba(255,255,255,0.6); margin-top:3px; display:block; }
    .nav-btns { display:flex; gap:8px; }
    .nav-btn {
        width:36px; height:36px; border-radius:50%;
        border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.08);
        color:#fff; cursor:pointer; font-size:0.85rem;
        display:flex; align-items:center; justify-content:center; transition:0.2s;
    }
    .nav-btn:hover { background:rgba(255,255,255,0.18); }
    .today-btn {
        padding:8px 16px; border-radius:20px;
        border:1px solid rgba(212,175,55,0.4); background:rgba(212,175,55,0.15);
        color:var(--gold-main); font-family:'Vazirmatn',sans-serif;
        font-size:0.8rem; font-weight:700; cursor:pointer; transition:0.2s;
    }
    .today-btn:hover { background:rgba(212,175,55,0.25); }

    .weekday-row {
        display:grid; grid-template-columns:repeat(7,1fr);
        background:#f8fafc; border-bottom:1px solid #f0f0f0;
        padding:10px 16px 6px;
    }
    .weekday-row span { text-align:center; font-size:0.72rem; font-weight:800; color:#94a3b8; }
    .weekday-row span.fri { color:#ef4444; }

    .days-grid { display:grid; grid-template-columns:repeat(7,1fr); padding:10px 12px 16px; gap:6px; }
    .day-cell {
        aspect-ratio:1; border-radius:10px; display:flex; flex-direction:column;
        align-items:center; justify-content:center; cursor:pointer;
        transition:0.2s; position:relative; border:2px solid transparent;
        font-size:0.88rem; font-weight:600; color:#475569;
    }
    .day-cell:hover { background:#f1f5f9; border-color:#e2e8f0; }
    .day-cell.today { background:var(--gold-main); color:#fff; font-weight:800; border-color:var(--gold-dark); }
    .day-cell.today:hover { background:var(--gold-dark); }
    .day-cell.selected { background:var(--navy); color:#fff; border-color:var(--navy); }
    .day-cell.fri { color:#ef4444; }
    .day-cell.today.fri { background:#ef4444; border-color:#dc2626; }
    .day-cell.other-month { color:#cbd5e1; }
    .day-cell.other-month .day-num { opacity:0.5; }
    .day-cell.has-event .event-dots { display:flex; gap:2px; margin-top:3px; }
    .event-dot { width:5px; height:5px; border-radius:50%; background:#3b82f6; }
    .event-dot.confirmed { background:#10b981; }
    .event-dot.pending   { background:#f59e0b; }
    .day-cell.today .event-dot, .day-cell.selected .event-dot { background:rgba(255,255,255,0.8); }

    /* ── پنل روز ── */
    .day-panel { display:flex; flex-direction:column; gap:16px; }

    .day-info-card { background:#fff; border-radius:14px; padding:22px; box-shadow:0 4px 15px rgba(0,0,0,0.05); }
    .dip-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; }
    .dip-header h3 { font-size:1rem; font-weight:800; color:var(--navy); margin:0; }
    .dip-date { font-size:0.8rem; color:#888; }

    .slot-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .slot-item {
        padding:10px 12px; border-radius:10px; text-align:center;
        font-size:0.82rem; font-weight:700; border:1.5px solid #e0e0e0;
        background:#f8fafc; cursor:pointer; transition:0.2s;
        display:flex; flex-direction:column; align-items:center; gap:3px;
    }
    .slot-item.free:hover { border-color:var(--gold-main); background:rgba(197,160,89,0.05); }
    .slot-item.booked { background:#fef3c7; border-color:#f59e0b; color:#b45309; cursor:default; }
    .slot-item.booked.confirmed { background:#d1fae5; border-color:#10b981; color:#065f46; }
    .slot-item.booked.cancelled { background:#f1f5f9; border-color:#e2e8f0; color:#94a3b8; text-decoration:line-through; }
    .slot-time { font-size:0.85rem; font-weight:800; }
    .slot-name { font-size:0.7rem; opacity:0.8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; }

    .no-slots { text-align:center; padding:24px; color:#aaa; font-size:0.85rem; }
    .no-slots i { font-size:2rem; display:block; margin-bottom:8px; opacity:0.3; }

    /* ── لیست رویدادها ── */
    .event-list { display:flex; flex-direction:column; gap:10px; }
    .event-item {
        padding:14px 16px; border-radius:12px; background:#f8fafc;
        border-right:4px solid var(--gold-main); transition:0.2s;
        text-decoration:none; color:inherit; display:block;
    }
    .event-item:hover { background:#f0fdf4; border-right-color:#10b981; }
    .event-item.pending   { border-right-color:#f59e0b; background:#fffbeb; }
    .event-item.confirmed { border-right-color:#10b981; background:#f0fdf4; }
    .event-item.cancelled { border-right-color:#94a3b8; background:#f8fafc; opacity:0.6; }
    .ei-time { font-size:0.75rem; font-weight:800; color:var(--gold-dark); margin-bottom:4px; }
    .ei-name { font-size:0.9rem; font-weight:700; color:var(--navy); margin-bottom:3px; }
    .ei-type { font-size:0.75rem; color:#888; display:flex; align-items:center; gap:5px; }

    .badge { padding:3px 9px; border-radius:20px; font-size:0.7rem; font-weight:700; }
    .badge-pending   { background:#fef3c7; color:#b45309; }
    .badge-confirmed { background:#d1fae5; color:#065f46; }
    .badge-cancelled { background:#f1f5f9; color:#64748b; }

    /* ── آمار ماه ── */
    .month-stats { background:#fff; border-radius:14px; padding:20px; box-shadow:0 4px 15px rgba(0,0,0,0.05); }
    .month-stats h4 { font-size:0.88rem; font-weight:800; color:var(--navy); margin-bottom:14px; display:flex; align-items:center; gap:7px; }
    .month-stats h4 i { color:var(--gold-main); }
    .ms-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .ms-item { text-align:center; background:#f8fafc; padding:12px; border-radius:10px; }
    .ms-item .n { font-size:1.3rem; font-weight:900; color:var(--navy); display:block; }
    .ms-item .l { font-size:0.7rem; color:#888; }

    @media(max-width:960px) { .cal-page { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<div class="cal-page">

    {{-- ── تقویم ── --}}
    <div>
        <div class="calendar-card">
            <div class="cal-nav-bar">
                <div>
                    <h2 id="calTitle">در حال بارگذاری...</h2>
                    <span id="calSubtitle"></span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <button class="today-btn" onclick="goToday()">امروز</button>
                    <div class="nav-btns">
                        <button class="nav-btn" onclick="changeMonth(-1)"><i class="fas fa-chevron-right"></i></button>
                        <button class="nav-btn" onclick="changeMonth(1)"><i class="fas fa-chevron-left"></i></button>
                    </div>
                </div>
            </div>
            <div class="weekday-row">
                <span>ش</span><span>ی</span><span>د</span><span>س</span><span>چ</span><span>پ</span><span class="fri">ج</span>
            </div>
            <div class="days-grid" id="calDays"></div>
        </div>
    </div>

    {{-- ── پنل کناری ── --}}
    <div class="day-panel">

        {{-- اطلاعات روز انتخابی --}}
        <div class="day-info-card" id="dayInfoCard">
            <div class="dip-header">
                <h3 id="selectedDayTitle">امروز</h3>
                <span class="dip-date" id="selectedDayDate"></span>
            </div>
            <div id="dayContent">
                <div class="no-slots">
                    <i class="fas fa-mouse-pointer"></i>
                    یک روز را از تقویم انتخاب کنید
                </div>
            </div>
        </div>

        {{-- آمار ماه --}}
        <div class="month-stats">
            <h4><i class="fas fa-chart-bar"></i> آمار این ماه</h4>
            <div class="ms-grid">
                <div class="ms-item"><span class="n" id="statTotal">—</span><span class="l">کل مشاوره</span></div>
                <div class="ms-item"><span class="n" id="statConfirmed">—</span><span class="l">تأیید شده</span></div>
                <div class="ms-item"><span class="n" id="statPending">—</span><span class="l">در انتظار</span></div>
                <div class="ms-item"><span class="n" id="statCancelled">—</span><span class="l">لغو شده</span></div>
            </div>
        </div>

        {{-- لیست مشاوره‌های انتخاب شده --}}
        <div class="day-info-card" id="dayEventsCard" style="display:none;">
            <div class="dip-header">
                <h3><i class="fas fa-list" style="color:var(--gold-main);margin-left:6px;"></i> مشاوره‌های این روز</h3>
            </div>
            <div id="dayEventsList"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── داده‌ها از سرور ──
const consultations = @json(
    \App\Models\Consultation::where('lawyer_id', auth('lawyer')->id())
        ->whereYear('scheduled_at', now()->year)
        ->with('user')
        ->get()
        ->map(fn($c) => [
            'id'           => $c->id,
            'user_name'    => $c->user->name ?? 'موکل',
            'type'         => $c->type,
            'status'       => $c->status,
            'scheduled_at' => $c->scheduled_at?->format('Y-m-d H:i'),
            'jalali_date'  => $c->scheduled_at ? \Morilog\Jalali\Jalalian::fromCarbon($c->scheduled_at)->format('Y/m/d') : null,
            'time'         => $c->scheduled_at?->format('H:i'),
        ])
);

const schedules = @json(
    \App\Models\LawyerSchedule::where('lawyer_id', auth('lawyer')->id())
        ->get()
        ->keyBy('day_of_week')
        ->map(fn($s) => ['is_available'=>$s->is_available,'start'=>substr($s->start_time??'09:00',0,5),'end'=>substr($s->end_time??'17:00',0,5)])
);

const exceptions = @json(
    \App\Models\LawyerScheduleException::where('lawyer_id', auth('lawyer')->id())
        ->get()
        ->map(fn($e) => ['date'=>$e->exception_date->format('Y-m-d'),'is_available'=>$e->is_available])
);

// ── ثابت‌ها ──
const jMonths   = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
const jDaysInM  = [31,31,31,31,31,31,30,30,30,30,30,29];
const typeLabel = {appointment:'حضوری',call:'تلفنی',chat:'چت آنلاین'};
const typeIcon  = {appointment:'fa-calendar-check',call:'fa-phone',chat:'fa-comment'};

// ── تاریخ امروز شمسی ──
const todayStr  = @json(\Morilog\Jalali\Jalalian::now()->format('Y/m/d'));
const [TY, TM, TD] = todayStr.split('/').map(Number);
let curYear = TY, curMonth = TM, selectedDate = null;

// ── گروه‌بندی مشاوره‌ها بر اساس تاریخ شمسی ──
const byDate = {};
consultations.forEach(c => {
    if (!c.jalali_date) return;
    const d = c.jalali_date.replace(/\//g,'-');
    if (!byDate[d]) byDate[d] = [];
    byDate[d].push(c);
});

// ── رندر تقویم ──
function renderCalendar(year, month) {
    document.getElementById('calTitle').textContent = jMonths[month-1];
    document.getElementById('calSubtitle').textContent = year;

    const daysInMonth = jDaysInM[month-1] + (month===12 && year%4===3 ? 1 : 0);

    // روز اول ماه (شنبه=0)
    const greg = jalaliToGreg(year, month, 1);
    const dow  = new Date(greg.y, greg.m-1, greg.d).getDay(); // 0=Sun
    const firstDay = (dow + 1) % 7; // شنبه=0

    // آمار ماه
    let total=0, conf=0, pend=0, canc=0;
    for (let d=1; d<=daysInMonth; d++) {
        const key = `${year}-${String(month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        (byDate[key]||[]).forEach(c => {
            total++;
            if (c.status==='confirmed') conf++;
            else if (c.status==='pending') pend++;
            else if (c.status==='cancelled') canc++;
        });
    }
    document.getElementById('statTotal').textContent     = total;
    document.getElementById('statConfirmed').textContent = conf;
    document.getElementById('statPending').textContent   = pend;
    document.getElementById('statCancelled').textContent = canc;

    let html = '';
    for (let i=0; i<firstDay; i++) html += '<div class="day-cell other-month"></div>';

    for (let d=1; d<=daysInMonth; d++) {
        const key  = `${year}-${String(month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const events = byDate[key] || [];
        const isToday    = d===TD && month===TM && year===TY;
        const isSelected = selectedDate === key;
        const isFri = (firstDay + d - 1) % 7 === 6;

        let cls = 'day-cell';
        if (isToday)    cls += ' today';
        if (isSelected) cls += ' selected';
        if (isFri)      cls += ' fri';
        if (events.length) cls += ' has-event';

        // بررسی تعطیل/در دسترس
        const exc = exceptions.find(e => e.date === key);
        const isUnavail = (exc && !exc.is_available) || isFri;
        if (isUnavail) cls += ' unavail';

        const dots = events.slice(0,3).map(e => `<div class="event-dot ${e.status}"></div>`).join('');

        html += `<div class="${cls}" onclick="selectDay('${key}',${d},${year},${month})">
            <span class="day-num">${d}</span>
            ${events.length ? `<div class="event-dots">${dots}</div>` : ''}
        </div>`;
    }

    document.getElementById('calDays').innerHTML = html;
}

// ── انتخاب روز ──
function selectDay(key, d, year, month) {
    selectedDate = key;
    renderCalendar(year, month);

    const jDate = `${year}/${String(month).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
    document.getElementById('selectedDayTitle').textContent = `${d} ${jMonths[month-1]} ${year}`;
    document.getElementById('selectedDayDate').textContent = key;

    const events = byDate[key] || [];
    const dayOfWeek = (new Date(...jalaliToGreg(year,month,d).map((v,i)=>i===1?v-1:v) |> []).getDay() + 1) % 7;

    // اسلات‌ها
    const exc = exceptions.find(e => e.date === key);
    const isFri = (function(){
        const g = jalaliToGreg(year,month,d);
        return new Date(g.y,g.m-1,g.d).getDay() === 5;
    })();

    let workStart = '09:00', workEnd = '17:00';
    const sch = schedules[dayOfWeek];
    if (exc && !exc.is_available) {
        renderDayContent([], events, true, 'این روز تعطیل است.');
    } else if (isFri) {
        renderDayContent([], events, true, 'جمعه تعطیل است.');
    } else {
        if (sch) {
            if (!sch.is_available) {
                renderDayContent([], events, true, 'این روز خارج از ساعات کاری است.');
                return;
            }
            workStart = sch.start; workEnd = sch.end;
        }
        const slots = buildSlots(workStart, workEnd, key, events);
        renderDayContent(slots, events, false, '');
    }
}

function buildSlots(start, end, key, events) {
    const slots = [];
    let [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    const endMins = eh*60+em;

    while (sh*60+sm < endMins) {
        const timeStr = `${String(sh).padStart(2,'0')}:${String(sm).padStart(2,'0')}`;
        const booked  = events.find(e => e.time === timeStr);
        slots.push({ time: timeStr, booked, endTime: addMins(sh, sm, 30) });
        const next = addMinsNum(sh*60+sm, 30);
        sh = Math.floor(next/60); sm = next%60;
    }
    return slots;
}

function addMins(h, m, add) {
    const t = h*60+m+add;
    return `${String(Math.floor(t/60)).padStart(2,'0')}:${String(t%60).padStart(2,'0')}`;
}
function addMinsNum(mins, add) { return mins+add; }

function renderDayContent(slots, events, closed, msg) {
    const cont = document.getElementById('dayContent');

    if (closed) {
        cont.innerHTML = `<div class="no-slots"><i class="fas fa-moon"></i>${msg}</div>`;
    } else if (!slots.length) {
        cont.innerHTML = `<div class="no-slots"><i class="fas fa-clock"></i>ساعات کاری تنظیم نشده</div>`;
    } else {
        let html = '<div class="slot-grid">';
        slots.forEach(s => {
            if (s.booked) {
                html += `<div class="slot-item booked ${s.booked.status}" title="${s.booked.user_name}">
                    <span class="slot-time">${s.time}</span>
                    <span class="slot-name">${s.booked.user_name}</span>
                </div>`;
            } else {
                html += `<div class="slot-item free">
                    <span class="slot-time">${s.time}</span>
                    <span class="slot-name" style="color:#94a3b8;">آزاد</span>
                </div>`;
            }
        });
        html += '</div>';
        cont.innerHTML = html;
    }

    // لیست رویدادها
    const evCard  = document.getElementById('dayEventsCard');
    const evList  = document.getElementById('dayEventsList');
    if (events.length) {
        evCard.style.display = 'block';
        evList.innerHTML = events.map(e => `
            <a href="/lawyer/consultations/${e.id}" class="event-item ${e.status}">
                <div class="ei-time">${e.time} <span class="badge badge-${e.status}">${{pending:'در انتظار',confirmed:'تأیید شده',completed:'تکمیل',cancelled:'لغو'}[e.status]||e.status}</span></div>
                <div class="ei-name">${e.user_name}</div>
                <div class="ei-type"><i class="fas ${typeIcon[e.type]||'fa-file'}"></i> ${typeLabel[e.type]||e.type}</div>
            </a>
        `).join('');
    } else {
        evCard.style.display = 'none';
    }
}

// ── تبدیل شمسی به میلادی ──
function jalaliToGreg(jy, jm, jd) {
    jy += 1595;
    let days = -355779 + (365*jy) + (Math.floor(jy/33)*8) + Math.floor(((jy%33)+3)/4) + jd;
    for (let i=1; i<jm; i++) days += jDaysInM[i-1];
    const gy = 400*Math.floor(days/146097); days %= 146097;
    const n  = Math.floor(days/36524);
    return { y: gy + (n === 4 ? 400 : n*100) + Math.floor((days%36524)/365.25), m: jm, d: jd };
}

function changeMonth(d) {
    curMonth += d;
    if (curMonth>12) { curMonth=1; curYear++; }
    if (curMonth<1)  { curMonth=12; curYear--; }
    renderCalendar(curYear, curMonth);
}

function goToday() {
    curYear = TY; curMonth = TM;
    renderCalendar(TY, TM);
    selectDay(
        `${TY}-${String(TM).padStart(2,'0')}-${String(TD).padStart(2,'0')}`,
        TD, TY, TM
    );
}

// ── init ──
document.addEventListener('DOMContentLoaded', () => {
    renderCalendar(TY, TM);
    // امروز را انتخاب کن
    const todayKey = `${TY}-${String(TM).padStart(2,'0')}-${String(TD).padStart(2,'0')}`;
    selectDay(todayKey, TD, TY, TM);
});
</script>
@endpush

@endsection