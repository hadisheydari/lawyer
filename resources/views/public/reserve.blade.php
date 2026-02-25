@extends('layouts.public')

@section('title', 'رزرو نوبت مشاوره')

@push('styles')
    <style>
        .reserve-wrapper {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .booking-card {
            background: #fff;
            width: 100%; max-width: 650px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            padding: 40px;
            border-top: 5px solid var(--gold-main);
            text-align: center;
        }
        .booking-title {
            font-size: 1.25rem; font-weight: 800; color: var(--navy);
            margin-bottom: 5px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .booking-title::before { content: ''; display: inline-block; width: 10px; height: 10px; background: var(--gold-main); border-radius: 50%; }
        .booking-subtitle { font-size: 0.88rem; color: #888; margin-bottom: 30px; }

        .lawyer-select {
            display: flex; gap: 15px; margin-bottom: 25px; justify-content: center;
        }
        .lawyer-opt {
            flex: 1; max-width: 200px;
            border: 2px solid #eee; border-radius: 10px; padding: 15px;
            cursor: pointer; transition: 0.3s; text-align: center;
        }
        .lawyer-opt:hover { border-color: var(--gold-light); }
        .lawyer-opt.selected { border-color: var(--gold-main); background: #fdfbf7; }
        .lawyer-opt .l-avatar { width: 50px; height: 50px; border-radius: 50%; background: var(--navy); color: var(--gold-main); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; margin: 0 auto 8px; }
        .lawyer-opt .l-name { font-size: 0.88rem; font-weight: 700; color: var(--navy); }
        .lawyer-opt .l-spec { font-size: 0.75rem; color: #888; margin-top: 3px; }

        .type-select { display: flex; gap: 10px; margin-bottom: 25px; justify-content: center; }
        .type-opt {
            flex: 1; max-width: 130px; border: 2px solid #eee; border-radius: 8px;
            padding: 12px 8px; cursor: pointer; transition: 0.3s; text-align: center;
        }
        .type-opt:hover { border-color: var(--gold-light); }
        .type-opt.selected { border-color: var(--gold-main); background: #fdfbf7; }
        .type-opt i { font-size: 1.3rem; display: block; margin-bottom: 5px; }
        .type-opt span { font-size: 0.8rem; font-weight: 600; color: var(--navy); }
        .type-opt .price { font-size: 0.72rem; color: #888; display: block; margin-top: 2px; }

        .calendar-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .month-label { font-size: 1.05rem; font-weight: 700; color: var(--navy); }
        .nav-btn { background: none; border: 1px solid #eee; border-radius: 6px; color: var(--navy); font-size: 1rem; cursor: pointer; padding: 6px 10px; transition: 0.3s; }
        .nav-btn:hover { border-color: var(--gold-main); color: var(--gold-main); }

        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-bottom: 25px; }
        .weekday { font-size: 0.78rem; color: #888; font-weight: 600; padding-bottom: 8px; }
        .weekday.friday { color: #e74c3c; }
        .day {
            aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 0.9rem; cursor: pointer; transition: 0.2s;
            background: #fff; color: var(--navy); border: 1px solid transparent;
        }
        .day.disabled { background: #f5f5f5; color: #ccc; cursor: not-allowed; }
        .day:not(.disabled):not(.selected):hover { border-color: var(--gold-main); color: var(--gold-main); }
        .day.selected { background: var(--navy); color: #fff; border: 2px solid var(--gold-main); box-shadow: 0 4px 12px rgba(16,42,67,0.25); font-weight: bold; transform: scale(1.1); }
        .day.holiday { color: #e74c3c; }
        .day.holiday.disabled { color: #f5c6c4; }

        .action-area {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px;
        }
        .btn-book {
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
            color: #fff; border: none; padding: 12px 28px; border-radius: 8px;
            font-weight: 700; font-size: 0.92rem; cursor: pointer;
            display: flex; align-items: center; gap: 8px; transition: 0.3s;
            font-family: 'Vazirmatn', sans-serif;
            box-shadow: 0 5px 15px rgba(16,42,67,0.2);
        }
        .btn-book:hover { transform: translateY(-2px); }
        .btn-book:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-back {
            background: transparent; color: #888; border: 1px solid #ddd;
            padding: 10px 22px; border-radius: 8px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 8px;
            transition: 0.3s; font-family: 'Vazirmatn', sans-serif; text-decoration: none;
        }
        .btn-back:hover { border-color: var(--navy); color: var(--navy); }
        .legend { display: flex; justify-content: center; gap: 18px; margin-top: 20px; font-size: 0.78rem; color: #888; }
        .legend-item { display: flex; align-items: center; gap: 5px; }
        .dot { width: 11px; height: 11px; border-radius: 3px; display: inline-block; }

        .selected-info { background: #fdfbf7; border: 1px solid var(--gold-light); border-radius: 8px; padding: 12px 16px; margin-top: 15px; font-size: 0.88rem; color: var(--navy); display: none; }
        .selected-info.show { display: block; }

        @media (max-width: 500px) {
            .booking-card { padding: 25px 15px; }
            .calendar-grid { gap: 5px; }
            .action-area { flex-direction: column-reverse; gap: 12px; }
            .btn-book, .btn-back { width: 100%; justify-content: center; }
            .lawyer-select { flex-direction: column; align-items: center; }
            .type-select { flex-wrap: wrap; }
        }
    </style>
@endpush

@section('content')
    <div class="reserve-wrapper">
        <div class="booking-card">

            <h1 class="booking-title">رزرو نوبت مشاوره</h1>
            <p class="booking-subtitle">وکیل، نوع مشاوره و تاریخ مورد نظر را انتخاب کنید</p>

            @if(session('success'))
                <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:0.9rem;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('reserve.store') ?? '#' }}" id="reserveForm">
                @csrf

                {{-- انتخاب وکیل --}}
                <div style="font-weight:700;color:var(--navy);margin-bottom:12px;text-align:right;">
                    <i class="fas fa-user-tie" style="color:var(--gold-main);margin-left:5px;"></i> انتخاب وکیل
                </div>
                <div class="lawyer-select">
                    @foreach($lawyers ?? [] as $lawyer)
                        <label class="lawyer-opt" id="lawyer-{{ $lawyer->id }}">
                            <input type="radio" name="lawyer_id" value="{{ $lawyer->id }}" style="display:none;"
                                   onchange="selectLawyer({{ $lawyer->id }})">
                            <div class="l-avatar">{{ mb_substr($lawyer->name, 0, 1) }}</div>
                            <div class="l-name">{{ $lawyer->name }}</div>
                            <div class="l-spec">{{ implode('، ', array_slice($lawyer->specializations ?? [], 0, 2)) }}</div>
                        </label>
                    @endforeach

                    {{-- اگر وکلا از دیتابیس نیامدن، نمونه --}}
                    @if(empty($lawyers) || count($lawyers) === 0)
                        <div class="lawyer-opt selected" onclick="this.querySelector('input').click()">
                            <input type="radio" name="lawyer_id" value="1" style="display:none;" checked>
                            <div class="l-avatar">ا</div>
                            <div class="l-name">بابک ابدالی</div>
                            <div class="l-spec">دعاوی ملکی و تجاری</div>
                        </div>
                        <div class="lawyer-opt" onclick="this.querySelector('input').click();document.querySelectorAll('.lawyer-opt').forEach(e=>e.classList.remove('selected'));this.classList.add('selected')">
                            <input type="radio" name="lawyer_id" value="2" style="display:none;">
                            <div class="l-avatar">ج</div>
                            <div class="l-name">زهرا جوشقانی</div>
                            <div class="l-spec">خانواده و طلاق</div>
                        </div>
                    @endif
                </div>

                {{-- نوع مشاوره --}}
                <div style="font-weight:700;color:var(--navy);margin-bottom:12px;text-align:right;">
                    <i class="fas fa-list" style="color:var(--gold-main);margin-left:5px;"></i> نوع مشاوره
                </div>
                <div class="type-select" id="typeSelect">
                    <label class="type-opt selected" onclick="selectType(this,'chat')">
                        <input type="radio" name="consultation_type" value="chat" style="display:none;" checked>
                        <i class="fas fa-comment-dots" style="color:#3498db;"></i>
                        <span>چت متنی</span>
                        <span class="price">از ۱۰۰,۰۰۰ تومان</span>
                    </label>
                    <label class="type-opt" onclick="selectType(this,'call')">
                        <input type="radio" name="consultation_type" value="call" style="display:none;">
                        <i class="fas fa-phone" style="color:#2ecc71;"></i>
                        <span>تماس تلفنی</span>
                        <span class="price">هر دقیقه ۵,۰۰۰ تومان</span>
                    </label>
                    <label class="type-opt" onclick="selectType(this,'appointment')">
                        <input type="radio" name="consultation_type" value="appointment" style="display:none;">
                        <i class="fas fa-building" style="color:#f39c12;"></i>
                        <span>حضوری</span>
                        <span class="price">از ۳۰۰,۰۰۰ تومان</span>
                    </label>
                </div>

                {{-- تقویم --}}
                <div class="calendar-nav">
                    <button type="button" class="nav-btn" onclick="prevMonth()">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <span class="month-label" id="monthLabel">در حال بارگذاری...</span>
                    <button type="button" class="nav-btn" onclick="nextMonth()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>

                <div class="calendar-grid" id="calendarGrid">
                    <div class="weekday">ش</div>
                    <div class="weekday">ی</div>
                    <div class="weekday">د</div>
                    <div class="weekday">س</div>
                    <div class="weekday">چ</div>
                    <div class="weekday">پ</div>
                    <div class="weekday friday">ج</div>
                </div>

                <input type="hidden" name="selected_date" id="selectedDate">

                <div class="selected-info" id="selectedInfo">
                    <i class="fas fa-calendar-check" style="color:var(--gold-main);margin-left:5px;"></i>
                    تاریخ انتخاب شده: <strong id="selectedDateLabel">—</strong>
                </div>

                <div class="action-area">
                    <button type="submit" class="btn-book" id="bookBtn" disabled>
                        رزرو نوبت <i class="fas fa-check-circle"></i>
                    </button>
                    <a href="{{ route('home') }}" class="btn-back">
                        بازگشت <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </form>

            <div class="legend">
                <div class="legend-item">
                    <span class="dot" style="background:var(--navy);border:1px solid var(--gold-main);"></span> روز انتخابی
                </div>
                <div class="legend-item">
                    <span class="dot" style="background:#f0f0f0;"></span> روز بدون نوبت
                </div>
                <div class="legend-item">
                    <span class="dot" style="background:#fff;border:1px solid var(--gold-main);"></span> نوبت آزاد
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // تقویم ساده شمسی
            const months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
            // دریافت تاریخ شمسی فعلی از سرور
            let currentMonth = {{ now()->month }};
            let currentYear  = 1404; // باید از سرور بیاد

            const disabledDates = @json($disabledDates ?? []);

            function toPersianNum(n) {
                const d = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
                return String(n).replace(/\d/g, c => d[+c]);
            }

            function renderCalendar() {
                document.getElementById('monthLabel').textContent = months[currentMonth - 1] + ' ' + toPersianNum(currentYear);

                const grid = document.getElementById('calendarGrid');
                // فقط header ها رو نگه دار
                while (grid.children.length > 7) grid.removeChild(grid.lastChild);

                // تعداد روزهای ماه (ساده‌شده)
                const daysInMonth = currentMonth <= 6 ? 31 : (currentMonth <= 11 ? 30 : 29);
                const firstDay = 0; // شنبه (ساده‌سازی)

                for (let i = 0; i < firstDay; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'day disabled';
                    grid.appendChild(empty);
                }

                for (let d = 1; d <= daysInMonth; d++) {
                    const dateStr = `${currentYear}/${String(currentMonth).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
                    const dayEl = document.createElement('div');
                    const colIndex = (firstDay + d - 1) % 7;
                    const isFriday = colIndex === 6;
                    const isDisabled = disabledDates.includes(dateStr) || isFriday;

                    dayEl.className = 'day' + (isDisabled ? ' disabled' : '') + (isFriday ? ' holiday' : '');
                    dayEl.textContent = toPersianNum(d);

                    if (!isDisabled) {
                        dayEl.onclick = () => selectDay(dayEl, dateStr, d);
                    }
                    grid.appendChild(dayEl);
                }
            }

            function selectDay(el, dateStr, day) {
                document.querySelectorAll('.day.selected').forEach(e => e.classList.remove('selected'));
                el.classList.add('selected');
                document.getElementById('selectedDate').value = dateStr;
                document.getElementById('selectedDateLabel').textContent = toPersianNum(day) + ' ' + months[currentMonth-1] + ' ' + toPersianNum(currentYear);
                document.getElementById('selectedInfo').classList.add('show');
                document.getElementById('bookBtn').disabled = false;
            }

            function prevMonth() {
                if (currentMonth === 1) { currentMonth = 12; currentYear--; }
                else currentMonth--;
                renderCalendar();
            }

            function nextMonth() {
                if (currentMonth === 12) { currentMonth = 1; currentYear++; }
                else currentMonth++;
                renderCalendar();
            }

            function selectType(el, type) {
                document.querySelectorAll('.type-opt').forEach(e => e.classList.remove('selected'));
                el.classList.add('selected');
            }

            function selectLawyer(id) {
                document.querySelectorAll('.lawyer-opt').forEach(e => e.classList.remove('selected'));
                document.getElementById('lawyer-' + id).classList.add('selected');
            }

            renderCalendar();
        </script>
    @endpush

@endsection
