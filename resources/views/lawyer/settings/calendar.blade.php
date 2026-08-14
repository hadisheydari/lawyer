@extends('layouts.lawyer')
@section('title', 'تقویم کاری')

@push('styles')
<style>
    .calendar-section { display: grid; grid-template-columns: 3fr 1fr; gap: 30px; }
    
    .calendar-card {
        background: #fff; border-radius: 12px; padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .current-month { font-size: 1.5rem; font-weight: 800; color: var(--navy); }
    .cal-nav button { background: none; border: 1px solid #eee; padding: 5px 10px; border-radius: 5px; cursor: pointer; color: #4b5563; transition: 0.3s;}
    .cal-nav button:hover { background: var(--navy); color: #fff; }

    .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
    .cal-day-name { text-align: center; font-weight: 700; color: #9ca3af; margin-bottom: 10px; font-size: 0.9rem; }

    .cal-cell {
        border: 1px solid #f3f4f6; border-radius: 8px; min-height: 100px;
        padding: 10px; transition: 0.3s; position: relative;
        display: flex; flex-direction: column;
    }
    .cal-cell:hover { border-color: var(--gold-main); background: #fdfbf7; }
    .cal-date { font-weight: 700; color: #d1d5db; position: absolute; top: 10px; left: 10px; z-index: 1;}
    .cal-cell.today { border: 2px solid var(--navy); }
    .cal-cell.today .cal-date { color: var(--navy); }
    
    .event-space { height: 25px; width: 100%; display: block; flex-shrink: 0; }

    .event {
        font-size: 0.75rem; padding: 4px 8px; border-radius: 4px;
        margin-bottom: 5px; cursor: pointer; position: relative; z-index: 2;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        transition: 0.2s; font-weight: bold; text-decoration: none; display: block;
    }
    .event:hover { filter: brightness(0.9); }
    .event.court { background: #fee2e2; color: #b91c1c; border-right: 3px solid #b91c1c; }
    .event.meeting { background: #fef3c7; color: #b45309; border-right: 3px solid #b45309; }
    .event.chat { background: #e0e7ff; color: #3730a3; border-right: 3px solid #3730a3; }

    .upcoming-card {
        background: var(--navy); color: #fff; border-radius: 12px;
        padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .up-title { font-size: 1.1rem; color: var(--gold-main); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); }

    .up-item { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-start;}
    .up-date {
        background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 8px;
        text-align: center; min-width: 55px;
    }
    .up-date.highlight { background: rgba(212, 175, 55, 0.2); color: var(--gold-main); }
    .up-day { display: block; font-size: 1.2rem; font-weight: 800; line-height: 1; margin-bottom: 3px;}
    .up-month { font-size: 0.7rem; color: #d1d5db; }

    .up-info h4 { margin: 0 0 5px; font-size: 0.9rem; font-weight: 700; color: #fff; }
    .up-info p { margin: 0; font-size: 0.8rem; color: #9ca3af; }

    @media (max-width: 1024px) {
        .calendar-section { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
    .cal-grid { gap: 4px; }
    .cal-cell { min-height: 55px; padding: 4px; }
    .cal-date { font-size: 0.75rem; }
    .event { font-size: 0.62rem; padding: 2px 4px; }
    .up-date { min-width: 45px; padding: 4px 6px; }
}
</style>
@endpush

@section('content')
<div class="calendar-section">
    
    <div class="calendar-card">
        <div class="cal-header">
            <div class="cal-nav">
                <button onclick="changeMonth(1)" title="ماه بعد"><i class="fas fa-chevron-right"></i></button>
                <button onclick="goToday()" title="امروز" style="margin: 0 5px; font-size: 0.8rem; font-family: inherit;">امروز</button>
                <button onclick="changeMonth(-1)" title="ماه قبل"><i class="fas fa-chevron-left"></i></button>
            </div>
            <span class="current-month" id="calMonthYear">در حال بارگذاری...</span>
        </div>

        <div class="cal-grid" id="calGrid">
            </div>
    </div>

    <div class="upcoming-sidebar">
        <div class="upcoming-card" id="upcomingSidebar">
             </div>
    </div>

</div>
@endsection

@push('scripts')
@php
    $lawyerId = auth('lawyer')->id();

    // استخراج امن اطلاعات برای جلوگیری از باگ ParseError لاراول
    $consultationsData = \App\Models\Consultation::where('lawyer_id', $lawyerId)
        ->whereYear('scheduled_at', '>=', now()->year - 1)
        ->with('user')
        ->get()
        ->map(fn($c) => [
            'id'           => $c->id,
            'user_name'    => $c->user->name ?? 'موکل',
            'type'         => $c->type,
            'status'       => $c->status,
            'jalali_date'  => $c->scheduled_at ? \Morilog\Jalali\Jalalian::fromCarbon($c->scheduled_at)->format('Y/m/d') : null,
            'time'         => $c->scheduled_at ? $c->scheduled_at->format('H:i') : ''
        ]);
@endphp

<script>
    const consultations = @json($consultationsData);

    const jMonths = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    const jDaysInM = [31,31,31,31,31,31,30,30,30,30,30,29];
    
    // دیکشنری برای ظاهر رویدادها
    const typeClasses = { appointment: 'meeting', call: 'chat', chat: 'chat' };
    const typeLabels  = { appointment: 'حضوری', call: 'تلفنی', chat: 'چت آنلاین' };

    // گرفتن تاریخ شمسی امروز
    const todayStr = @json(\Morilog\Jalali\Jalalian::now()->format('Y/m/d'));
    const [TY, TM, TD] = todayStr.split('/').map(Number);
    let curYear = TY, curMonth = TM;

    // گروه‌بندی برنامه‌ها بر اساس تاریخ
    const eventsByDate = {};
    consultations.forEach(c => {
        if (!c.jalali_date || c.status === 'cancelled' || c.status === 'rejected') return; 
        const dateKey = c.jalali_date;
        if (!eventsByDate[dateKey]) eventsByDate[dateKey] = [];
        eventsByDate[dateKey].push(c);
    });

    // محاسبه تاریخ فردا برای سایدبار
    function getTomorrow(y, m, d) {
        let nd = d + 1, nm = m, ny = y;
        let daysInCurrentMonth = jDaysInM[m-1] + (m===12 && y%4===3 ? 1 : 0);
        if (nd > daysInCurrentMonth) { nd = 1; nm++; }
        if (nm > 12) { nm = 1; ny++; }
        return {y: ny, m: nm, d: nd};
    }

    function jalaliToGreg(jy, jm, jd) {
        jy += 1595;
        let days = -355779 + (365*jy) + (Math.floor(jy/33)*8) + Math.floor(((jy%33)+3)/4) + jd;
        for (let i=1; i<jm; i++) days += jDaysInM[i-1];
        let gy = 400 * Math.floor(days / 146097); days %= 146097;
        let n = Math.floor(days / 36524);
        gy += (n === 4 ? 400 : n * 100) + Math.floor((days % 36524) / 365.25);
        return { y: gy, m: jm, d: jd }; // خروجی تقریبی برای محاسبه روز هفته
    }

    function renderCalendar(year, month) {
        document.getElementById('calMonthYear').textContent = jMonths[month-1] + ' ' + year;

        let daysInMonth = jDaysInM[month-1] + (month===12 && year%4===3 ? 1 : 0);
        let greg = jalaliToGreg(year, month, 1);
        let dow = new Date(greg.y, greg.m-1, greg.d).getDay();
        let firstDay = (dow + 1) % 7; // تبدیل به شنبه=0

        let html = '';
        const dayNames = ['شنبه', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'جمعه'];
        dayNames.forEach(d => html += `<div class="cal-day-name">${d}</div>`);

        for (let i=0; i<firstDay; i++) {
            html += `<div class="cal-cell" style="background:#f9fafb;"></div>`;
        }

        for (let d=1; d<=daysInMonth; d++) {
            let key = `${year}/${String(month).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
            let dailyEvents = eventsByDate[key] || [];
            
            let isToday = (d===TD && month===TM && year===TY);
            let isFri = ((firstDay + d - 1) % 7 === 6);

            let bgStyle = isFri ? 'background:#fff1f2;' : '';
            let dateColor = isFri ? 'color:#e11d48;' : '';

            let eventsHtml = '';
            // نمایش حداکثر 3 رویداد در هر خانه
            dailyEvents.slice(0, 3).forEach(e => {
                let cls = typeClasses[e.type] || 'court';
                eventsHtml += `<a href="/lawyer/consultations/${e.id}" class="event ${cls}" title="${e.user_name} - ${e.time}">
                                ${e.user_name} (${e.time})
                               </a>`;
            });
            if(dailyEvents.length > 3) {
                eventsHtml += `<div class="event" style="text-align:center; background:#f3f4f6; color:#6b7280;">+${dailyEvents.length - 3} مورد دیگر</div>`;
            }

            html += `
                <div class="cal-cell ${isToday ? 'today' : ''}" style="${bgStyle}">
                    <span class="cal-date" style="${dateColor}">${d}</span>
                    <span class="event-space"></span>
                    ${eventsHtml}
                </div>
            `;
        }
        document.getElementById('calGrid').innerHTML = html;
        renderUpcomingSidebar();
    }

    function renderUpcomingSidebar() {
        let html = '';
        let todayKey = `${TY}/${String(TM).padStart(2,'0')}/${String(TD).padStart(2,'0')}`;
        let todayEvents = eventsByDate[todayKey] || [];
        
        let tom = getTomorrow(TY, TM, TD);
        let tomorrowKey = `${tom.y}/${String(tom.m).padStart(2,'0')}/${String(tom.d).padStart(2,'0')}`;
        let tomorrowEvents = eventsByDate[tomorrowKey] || [];

        // مرتب سازی بر اساس ساعت
        todayEvents.sort((a,b) => a.time.localeCompare(b.time));
        tomorrowEvents.sort((a,b) => a.time.localeCompare(b.time));

        html += `<div class="up-title">برنامه امروز</div>`;
        if(todayEvents.length === 0) {
            html += `<p style="color:#9ca3af; font-size:0.85rem; margin-bottom:20px;">برنامه‌ای برای امروز ثبت نشده است.</p>`;
        } else {
            todayEvents.forEach(e => {
                html += `
                <div class="up-item">
                    <div class="up-date highlight">
                        <span class="up-day">${TD}</span>
                        <span class="up-month">${jMonths[TM-1]}</span>
                    </div>
                    <div class="up-info">
                        <h4>${typeLabels[e.type] || 'مشاوره'} ${e.user_name}</h4>
                        <p>ساعت: ${e.time}</p>
                    </div>
                </div>`;
            });
        }

        html += `<div class="up-title" style="margin-top:30px;">فردا</div>`;
        if(tomorrowEvents.length === 0) {
            html += `<p style="color:#9ca3af; font-size:0.85rem;">برنامه‌ای برای فردا ثبت نشده است.</p>`;
        } else {
            tomorrowEvents.forEach(e => {
                html += `
                <div class="up-item">
                    <div class="up-date">
                        <span class="up-day">${tom.d}</span>
                        <span class="up-month">${jMonths[tom.m-1]}</span>
                    </div>
                    <div class="up-info">
                        <h4>${typeLabels[e.type] || 'مشاوره'} ${e.user_name}</h4>
                        <p>ساعت: ${e.time}</p>
                    </div>
                </div>`;
            });
        }

        document.getElementById('upcomingSidebar').innerHTML = html;
    }

    function changeMonth(step) {
        curMonth += step;
        if (curMonth > 12) { curMonth = 1; curYear++; }
        if (curMonth < 1) { curMonth = 12; curYear--; }
        renderCalendar(curYear, curMonth);
    }

    function goToday() {
        curYear = TY; curMonth = TM;
        renderCalendar(curYear, curMonth);
    }

    // لود اولیه تقویم
    document.addEventListener('DOMContentLoaded', () => {
        renderCalendar(TY, TM);
    });
</script>
@endpush