@extends('layouts.public')
@section('title', 'رزرو نوبت مشاوره | ابدالی و جوشقانی')

@push('styles')
<style>
    /* ─── کدهای CSS خودت (بدون هیچ تغییری) ─── */
    .booking-wrapper { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
    
    .booking-card {
        background: var(--bg-white); width: 100%; max-width: 650px;
        border-radius: var(--radius-md); box-shadow: var(--shadow-card);
        padding: 40px; position: relative; z-index: 1;
        text-align: center; border-top: 5px solid var(--gold-main);
    }

    .booking-title { font-size: 1.3rem; font-weight: 800; color: var(--navy); margin-bottom: 5px; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .booking-title::before { content: ''; display: inline-block; width: 10px; height: 10px; background: var(--gold-main); border-radius: 50%; }
    .booking-subtitle { font-size: 0.9rem; color: var(--text-body); margin-bottom: 30px; opacity: 0.8; }

    /* Calendar Header */
    .calendar-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 0 20px; }
    .month-label { font-size: 1.1rem; font-weight: 700; color: var(--text-heading); }
    .nav-btn { background: none; border: none; color: var(--text-body); font-size: 1.2rem; cursor: pointer; transition: 0.3s; padding: 5px; text-decoration: none;}
    .nav-btn:hover { color: var(--gold-main); }

    /* Calendar Grid */
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; margin-bottom: 30px; }
    .weekday { font-size: 0.85rem; color: var(--text-body); font-weight: 600; padding-bottom: 10px; }
    .weekday.friday { color: #e74c3c; }

    .day {
        aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-size: 1rem; cursor: pointer; transition: 0.2s;
        background-color: #fff; color: var(--text-heading); border: 1px solid transparent;
        user-select: none; /* جلوگیری از سلکت شدن متن موقع کلیک سریع */
    }

    .day.disabled { background-color: #f8f9fa; color: #ccc; cursor: not-allowed; border: 1px solid #f1f1f1; }
    .day:not(.disabled):not(.selected):hover { border-color: var(--gold-main); color: var(--gold-main); background-color: #fcfcfc; }
    .day.selected { background-color: var(--navy); color: #fff; border: 2px solid var(--gold-main); box-shadow: 0 5px 15px rgba(16, 42, 67, 0.3); font-weight: bold; transform: scale(1.1); }
    
    .day.holiday { color: #e74c3c; }
    .day.holiday.disabled { color: #eecbc8; }

    /* Action Buttons */
    .action-area { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
    .btn-book {
        background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%); color: #fff;
        border: none; padding: 12px 30px; border-radius: 8px; font-weight: 700;
        font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 10px;
        transition: 0.3s; box-shadow: 0 5px 15px rgba(16, 42, 67, 0.2);
    }
    .btn-book:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16, 42, 67, 0.3); }
    .btn-book:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

    .btn-back { background: transparent; color: var(--text-body); border: 1px solid #ddd; padding: 10px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.3s; text-decoration: none; }
    .btn-back:hover { border-color: var(--navy); color: var(--navy); }

    /* Legend */
    .legend { display: flex; justify-content: center; gap: 20px; margin-top: 25px; font-size: 0.8rem; color: var(--text-body); }
    .legend-item { display: flex; align-items: center; gap: 6px; }
    .dot { width: 12px; height: 12px; border-radius: 4px; display: inline-block; }
    .dot.selected { background-color: var(--navy); border: 1px solid var(--gold-main); }
    .dot.disabled { background-color: #e0e0e0; }

    @media (max-width: 500px) {
        .booking-card { padding: 25px 15px; } .day { font-size: 0.9rem; } .calendar-grid { gap: 5px; }
        .action-area { flex-direction: column-reverse; gap: 15px; } .btn-book, .btn-back { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="booking-wrapper">
    <div class="booking-card">

        <h1 class="booking-title">
            تاریخ مورد نظر خود را انتخاب کنید
        </h1>
        <p class="booking-subtitle">
            @if($selectedLawyer)
                رزرو وقت مشاوره با <strong>{{ $selectedLawyer->name }}</strong>
            @else
                برای دریافت مشاوره حضوری، روزهای آزاد را بررسی کنید
            @endif
        </p>

        {{-- هدر تقویم (ماه‌ها) --}}
        <div class="calendar-nav">
            {{-- دکمه ماه بعد (چون فارسی راست به چپ است، دکمه سمت راست به ماه آینده می‌رود) --}}
            <a href="{{ route('reserve.index', ['year' => $nextYear, 'month' => $nextMonth, 'lawyer' => request('lawyer')]) }}" class="nav-btn">
                <i class="fas fa-arrow-right"></i>
            </a>
            
            <span class="month-label">{{ $monthName }} {{ $year }}</span>
            
            {{-- دکمه ماه قبل (فقط در صورتی نمایش داده شود که به گذشته نرویم) --}}
            @if($year > $nowYear || ($year == $nowYear && $month > $nowMonth))
                <a href="{{ route('reserve.index', ['year' => $prevYear, 'month' => $prevMonth, 'lawyer' => request('lawyer')]) }}" class="nav-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
            @else
                <span class="nav-btn" style="opacity: 0.2; cursor: default;"><i class="fas fa-arrow-left"></i></span>
            @endif
        </div>

        {{-- گرید اصلی تقویم --}}
        <div class="calendar-grid">
            <div class="weekday">شنبه</div>
            <div class="weekday">یکشنبه</div>
            <div class="weekday">دوشنبه</div>
            <div class="weekday">سه‌شنبه</div>
            <div class="weekday">چهارشنبه</div>
            <div class="weekday">پنج‌شنبه</div>
            <div class="weekday friday">جمعه</div>

            {{-- چاپ خانه‌های خالی اول ماه --}}
            @for ($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="day disabled"></div>
            @endfor

            {{-- چاپ روزهای ماه --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    // محاسبه روز هفته برای این تاریخ (جهت تشخیص جمعه‌ها)
                    $dayOfWeek = ($firstDayOfWeek + $day - 1) % 7;
                    $isFriday = $dayOfWeek == 6;

                    // بررسی اینکه آیا این روز گذشته است یا نه
                    $isPast = false;
                    if ($year < $nowYear) {
                        $isPast = true;
                    } elseif ($year == $nowYear && $month < $nowMonth) {
                        $isPast = true;
                    } elseif ($year == $nowYear && $month == $nowMonth && $day < $nowDay) {
                        $isPast = true;
                    }

                    // کلاس‌های CSS
                    $classes = 'day';
                    if ($isFriday)  $classes .= ' holiday';
                    if ($isPast || $isFriday) $classes .= ' disabled'; // جمعه‌ها و روزهای گذشته غیرفعال بشن
                @endphp

                <div class="{{ $classes }}" 
                     @if(!$isPast && !$isFriday) data-date="{{ $year }}-{{ sprintf('%02d', $month) }}-{{ sprintf('%02d', $day) }}" @endif>
                    {{ $day }}
                </div>
            @endfor
        </div>

        {{-- فرم ارسال --}}
        <form action="{{ route('reserve.store') }}" method="POST" id="reserveForm">
            @csrf
            {{-- ورودی مخفی برای ارسال تاریخ انتخاب شده به بک‌اند --}}
            <input type="hidden" name="selected_date" id="selectedDateInput" required>
            @if($selectedLawyer)
                <input type="hidden" name="lawyer_id" value="{{ $selectedLawyer->id }}">
            @endif

            <div class="action-area">
                <button type="submit" class="btn-book" id="submitBtn" disabled>
                    تایید و ادامه <i class="fas fa-arrow-left"></i>
                </button>

                <a href="{{ route('home') }}" class="btn-back">
                    بازگشت <i class="fas fa-times"></i>
                </a>
            </div>
        </form>

        <div class="legend">
            <div class="legend-item"><span class="dot selected"></span> روز انتخابی</div>
            <div class="legend-item"><span class="dot" style="background: #fff; border: 1px solid #ccc;"></span> روز آزاد</div>
            <div class="legend-item"><span class="dot disabled"></span> پر/تعطیل</div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const days = document.querySelectorAll('.day:not(.disabled)');
        const dateInput = document.getElementById('selectedDateInput');
        const submitBtn = document.getElementById('submitBtn');

        days.forEach(day => {
            day.addEventListener('click', function() {
                // پاک کردن کلاس سلکت از بقیه روزها
                document.querySelectorAll('.day.selected').forEach(el => {
                    el.classList.remove('selected');
                });

                // اضافه کردن کلاس سلکت به روز کلیک شده
                this.classList.add('selected');

                // ریختن تاریخ دقیق (مثل 1404-11-25) داخل اینپوت مخفی
                dateInput.value = this.getAttribute('data-date');

                // فعال کردن دکمه سابمیت
                submitBtn.disabled = false;
            });
        });
    });
</script>
@endpush