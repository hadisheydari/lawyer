@extends('layouts.public')

@section('title', 'رزرو نوبت مشاوره | دفتر وکالت ابدالی و جوشقانی')

@push('styles')
<style>
    :root {
        --pnav: #102a43;
        --pgold: #c5a059;
        --pgold-d: #9e7f41;
    }

    .reserve-section {
        padding: 60px 0 100px;
        background: linear-gradient(135deg, #fdfbf7 0%, #f5f0ea 100%);
        min-height: 80vh;
    }

    .booking-wrapper {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 30px;
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ─── Steps ─────────────────────────────────────────────── */
    .steps-container {
        grid-column: 1 / -1;
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
        gap: 0;
    }

    .step-item {
        display: flex; flex-direction: column; align-items: center;
        position: relative; flex: 1; max-width: 180px;
    }

    .step-item:not(:last-child)::after {
        content: '';
        position: absolute; top: 24px; right: -50%;
        width: 100%; height: 2px; background: #ddd; z-index: 0;
    }

    .step-item.completed:not(:last-child)::after { background: var(--pgold); }

    .step-number {
        width: 48px; height: 48px; border-radius: 50%;
        background: #fff; border: 2px solid #ddd;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; color: #999; position: relative; z-index: 1;
        transition: all 0.3s; font-size: 0.95rem;
    }

    .step-item.active .step-number {
        border-color: var(--pgold); background: var(--pnav);
        color: #fff; box-shadow: 0 0 0 4px rgba(197,160,89,0.2);
    }

    .step-item.completed .step-number {
        background: var(--pgold); border-color: var(--pgold); color: #fff;
    }

    .step-label {
        font-size: 0.82rem; font-weight: 700; color: #999; margin-top: 8px;
        text-align: center;
    }
    .step-item.active .step-label { color: var(--pnav); }
    .step-item.completed .step-label { color: var(--pgold-d); }

    /* ─── Cards ─────────────────────────────────────────────── */
    .booking-card {
        background: #fff; border-radius: 20px; padding: 35px;
        border: 1px solid rgba(197,160,89,0.15);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }

    .card-title {
        font-size: 1.2rem; font-weight: 900; color: var(--pnav);
        margin-bottom: 25px; display: flex; align-items: center; gap: 10px;
        padding-bottom: 15px; border-bottom: 2px solid #f5f0ea;
    }
    .card-title i { color: var(--pgold); }

    /* ─── Calendar ───────────────────────────────────────────── */
    .calendar-nav {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px;
    }
    .cal-nav-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 8px;
        border: 1px solid #e0e0e0; background: #fff;
        color: var(--pnav); font-family: 'Vazirmatn', sans-serif;
        font-size: 0.85rem; font-weight: 600; cursor: pointer;
        text-decoration: none; transition: 0.2s;
    }
    .cal-nav-btn:hover { border-color: var(--pgold); color: var(--pgold-d); }
    .cal-nav-btn.disabled { opacity: 0.35; pointer-events: none; }

    .month-label { font-size: 1.1rem; font-weight: 800; color: var(--pnav); }

    .calendar-grid {
        display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px;
    }

    .cal-head {
        text-align: center; font-weight: 700; font-size: 0.82rem;
        padding: 8px 0; color: #888;
    }
    .cal-head.friday { color: #dc2626; }

    .cal-day {
        aspect-ratio: 1; background: #fff; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; border: 1.5px solid #eee;
        font-size: 0.9rem; font-weight: 600; color: #333;
        transition: all 0.2s; position: relative; user-select: none;
    }
    .cal-day:hover:not(.disabled):not(.empty) {
        border-color: var(--pgold); color: var(--pgold-d);
        transform: translateY(-2px); box-shadow: 0 4px 10px rgba(197,160,89,0.15);
    }
    .cal-day.selected {
        background: var(--pnav); color: #fff;
        border-color: var(--pgold); box-shadow: 0 5px 15px rgba(16,42,67,0.2);
        transform: scale(1.1);
    }
    .cal-day.disabled {
        opacity: 0.3; cursor: not-allowed; background: #f9f9f9;
    }
    .cal-day.empty { border: none; background: transparent; cursor: default; }
    .cal-day.friday:not(.selected) { color: #dc2626; }
    .cal-day.has-slots::after {
        content: ''; position: absolute; bottom: 5px;
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--pgold); opacity: 0.7;
    }
    .cal-day.today:not(.selected) {
        border-color: var(--pgold); color: var(--pgold-d); font-weight: 800;
    }

    /* ─── Slots ──────────────────────────────────────────────── */
    .slots-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
    }

    .slot-btn {
        padding: 14px 10px; background: #fff; border: 2px solid #eee;
        border-radius: 12px; text-align: center; font-weight: 700;
        cursor: pointer; transition: all 0.2s; font-family: 'Vazirmatn', sans-serif;
        font-size: 0.9rem; color: var(--pnav);
    }
    .slot-btn:hover { border-color: var(--pgold); color: var(--pgold-d); }
    .slot-btn.selected {
        background: var(--pgold); color: #fff; border-color: var(--pgold);
        box-shadow: 0 5px 15px rgba(197,160,89,0.3);
    }
    .slot-btn small { display: block; font-size: 0.7rem; opacity: 0.7; margin-top: 3px; }

    .loader-spinner {
        text-align: center; padding: 40px;
        color: #aaa; font-size: 0.9rem;
    }
    .loader-spinner i { font-size: 1.5rem; display: block; margin-bottom: 8px;
        animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ─── Confirm Step ───────────────────────────────────────── */
    .confirm-info-box {
        background: #fdfbf7; border-radius: 12px; padding: 20px;
        border: 1px solid rgba(197,160,89,0.2); margin-bottom: 20px;
    }
    .confirm-row {
        display: flex; justify-content: space-between; padding: 10px 0;
        border-bottom: 1px solid #f0f0f0; font-size: 0.9rem;
    }
    .confirm-row:last-child { border-bottom: none; }
    .confirm-label { color: #888; }
    .confirm-value { font-weight: 700; color: var(--pnav); }

    .terms-check { display: flex; align-items: flex-start; gap: 10px; margin: 20px 0; font-size: 0.88rem; cursor: pointer; }
    .terms-check input { margin-top: 3px; accent-color: var(--pnav); width: 16px; height: 16px; flex-shrink: 0; }

    .btn-reserve-final {
        width: 100%; padding: 16px; border: none; border-radius: 12px;
        background: linear-gradient(135deg, var(--pgold), var(--pgold-d));
        color: #fff; font-family: 'Vazirmatn', sans-serif;
        font-size: 1rem; font-weight: 800; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 0 8px 20px rgba(197,160,89,0.35); transition: 0.3s;
    }
    .btn-reserve-final:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(197,160,89,0.5); }
    .btn-reserve-final:disabled { opacity: 0.45; filter: grayscale(0.5); cursor: not-allowed; }

    .btn-back-step {
        display: flex; align-items: center; gap: 6px;
        background: none; border: 1px solid #ddd; border-radius: 8px;
        padding: 10px 18px; font-family: 'Vazirmatn', sans-serif;
        font-size: 0.88rem; font-weight: 600; color: #666;
        cursor: pointer; margin-top: 10px; transition: 0.2s;
    }
    .btn-back-step:hover { border-color: var(--pnav); color: var(--pnav); }

    /* ─── Sidebar ────────────────────────────────────────────── */
    .sidebar-sticky { position: sticky; top: 90px; }

    .summary-card {
        background: var(--pnav); color: #fff;
        border-radius: 20px; padding: 25px;
        box-shadow: 0 10px 30px rgba(16,42,67,0.2);
    }

    .lawyer-mini {
        display: flex; align-items: center; gap: 14px;
        padding-bottom: 18px; margin-bottom: 18px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .lawyer-mini img {
        width: 56px; height: 56px; border-radius: 50%;
        object-fit: cover; border: 2px solid var(--pgold);
    }
    .lawyer-mini-placeholder {
        width: 56px; height: 56px; border-radius: 50%;
        background: rgba(197,160,89,0.2); border: 2px solid var(--pgold);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; font-weight: 900; color: var(--pgold); flex-shrink: 0;
    }
    .lawyer-mini h5 { margin: 0 0 3px; font-size: 0.95rem; font-weight: 700; }
    .lawyer-mini small { color: rgba(255,255,255,0.5); font-size: 0.78rem; }

    .sum-row {
        display: flex; justify-content: space-between;
        padding: 8px 0; font-size: 0.88rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .sum-row:last-child { border-bottom: none; }
    .sum-label { color: rgba(255,255,255,0.5); }
    .sum-value { font-weight: 700; color: var(--gold-light, #e6cfa3); }
    .sum-value.pending { color: rgba(255,255,255,0.3); font-weight: 400; font-style: italic; }

    .price-box {
        background: rgba(255,255,255,0.06); border-radius: 12px;
        padding: 18px; text-align: center; margin-top: 18px;
    }
    .price-box small { color: rgba(255,255,255,0.5); font-size: 0.78rem; display: block; margin-bottom: 5px; }
    .price-box strong { font-size: 1.3rem; font-weight: 900; }

    .help-box {
        background: #fff; border-radius: 14px; padding: 20px;
        margin-top: 20px; text-align: center;
        border: 1px solid rgba(197,160,89,0.2);
    }
    .help-box p { font-size: 0.82rem; color: #888; margin-bottom: 12px; }
    .help-box a {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--pnav); color: #fff;
        padding: 8px 16px; border-radius: 8px; font-size: 0.82rem;
        font-weight: 700; text-decoration: none; transition: 0.2s;
    }
    .help-box a:hover { background: var(--pgold); }

    @media (max-width: 900px) {
        .booking-wrapper { grid-template-columns: 1fr; }
        .sidebar-sticky { position: static; }
    }
    @media (max-width: 480px) {
        .booking-card { padding: 20px 15px; }
        .slots-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('content')

{{-- Page Banner --}}
<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-calendar-check" style="color:var(--gold-main);margin-left:12px;"></i>رزرو نوبت مشاوره</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <span>رزرو نوبت</span>
        </div>
    </div>
</div>

<section class="reserve-section">
    <div class="booking-wrapper">

        {{-- Steps Header --}}
        <div class="steps-container">
            <div class="step-item active" id="step-h1">
                <div class="step-number">۱</div>
                <div class="step-label">انتخاب روز</div>
            </div>
            <div class="step-item" id="step-h2">
                <div class="step-number">۲</div>
                <div class="step-label">انتخاب ساعت</div>
            </div>
            <div class="step-item" id="step-h3">
                <div class="step-number">۳</div>
                <div class="step-label">تأیید و پرداخت</div>
            </div>
        </div>

        {{-- Main --}}
        <div>

            {{-- Step 1: Calendar --}}
            <div class="booking-card" id="step-1">
                <h3 class="card-title"><i class="fas fa-calendar-alt"></i> انتخاب روز مشاوره</h3>

                {{-- Nav --}}
                <div class="calendar-nav">
                    @php
                        $now = \Morilog\Jalali\Jalalian::now();
                        $isCurrentMonth = ($calendar['year'] == $now->getYear() && $calendar['month'] == $now->getMonth());
                        $months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
                    @endphp

                    <a href="{{ route('reserve.index', ['lawyer' => $lawyer->slug, 'month' => $calendar['prev_month'], 'year' => $calendar['prev_year']]) }}"
                       class="cal-nav-btn {{ $isCurrentMonth ? 'disabled' : '' }}">
                        <i class="fas fa-chevron-right"></i> ماه قبل
                    </a>

                    <span class="month-label">{{ $months[$calendar['month'] - 1] }} {{ $calendar['year'] }}</span>

                    <a href="{{ route('reserve.index', ['lawyer' => $lawyer->slug, 'month' => $calendar['next_month'], 'year' => $calendar['next_year']]) }}"
                       class="cal-nav-btn">
                        ماه بعد <i class="fas fa-chevron-left"></i>
                    </a>
                </div>

                {{-- Grid --}}
                <div class="calendar-grid">
                    @foreach(['ش','ی','د','س','چ','پ','ج'] as $idx => $dayName)
                        <div class="cal-head {{ $idx == 6 ? 'friday' : '' }}">{{ $dayName }}</div>
                    @endforeach

                    @for($i = 0; $i < $calendar['start_day_of_week']; $i++)
                        <div class="cal-day empty"></div>
                    @endfor

                    @php $todayGregorian = now()->format('Y-m-d'); @endphp
                    @for($day = 1; $day <= $calendar['days_in_month']; $day++)
                        @php
                            $jalali = new \Morilog\Jalali\Jalalian($calendar['year'], $calendar['month'], $day);
                            $gregorianDate = $jalali->toCarbon()->format('Y-m-d');
                            $dayOfWeek = ($calendar['start_day_of_week'] + $day - 1) % 7;
                            $isFriday  = $dayOfWeek == 6;
                            $isPast    = $gregorianDate < $todayGregorian;
                            $isToday   = $gregorianDate === $todayGregorian;
                            $hasSlots  = isset($calendar['booked_dates'][$gregorianDate]);
                            $disabled  = $isFriday || $isPast;
                        @endphp
                        <div class="cal-day {{ $disabled ? 'disabled' : '' }} {{ $isFriday ? 'friday' : '' }} {{ $isToday ? 'today' : '' }} {{ $hasSlots ? 'has-slots' : '' }}"
                             @if(!$disabled)
                                 onclick="selectDay(this, '{{ $gregorianDate }}', '{{ $day }} {{ $months[$calendar['month']-1] }}')"
                                 data-date="{{ $gregorianDate }}"
                             @endif>
                            {{ $day }}
                        </div>
                    @endfor
                </div>

                {{-- Legend --}}
                <div style="display:flex;gap:20px;margin-top:20px;font-size:0.78rem;color:#888;flex-wrap:wrap;">
                    <span style="display:flex;align-items:center;gap:6px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:var(--pgold);display:inline-block;"></span>
                        دارای نوبت قبلی
                    </span>
                    <span style="display:flex;align-items:center;gap:6px;">
                        <span style="width:10px;height:10px;border-radius:2px;border:1.5px solid var(--pgold);display:inline-block;"></span>
                        امروز
                    </span>
                    <span style="display:flex;align-items:center;gap:6px;">
                        <span style="width:10px;height:10px;border-radius:2px;background:#f0f0f0;display:inline-block;opacity:0.4;"></span>
                        تعطیل / گذشته
                    </span>
                </div>
            </div>

            {{-- Step 2: Time Slots --}}
            <div class="booking-card" id="step-2" style="display:none;">
                <h3 class="card-title"><i class="fas fa-clock"></i> انتخاب ساعت</h3>
                <p style="color:#888;font-size:0.88rem;margin-bottom:20px;" id="slots-day-label"></p>

                <div id="slots-loader" class="loader-spinner" style="display:none;">
                    <i class="fas fa-spinner"></i>
                    در حال دریافت ساعت‌های موجود...
                </div>

                <div class="slots-grid" id="slots-container"></div>

                <button class="btn-back-step" onclick="goStep(1)">
                    <i class="fas fa-arrow-right"></i> تغییر روز
                </button>
            </div>

            {{-- Step 3: Confirm --}}
            <div class="booking-card" id="step-3" style="display:none;">
                <h3 class="card-title"><i class="fas fa-check-circle"></i> تأیید نهایی</h3>

                <div class="confirm-info-box">
                    <div class="confirm-row">
                        <span class="confirm-label">وکیل مشاور</span>
                        <span class="confirm-value">{{ $lawyer->name }}</span>
                    </div>
                    <div class="confirm-row">
                        <span class="confirm-label">تاریخ</span>
                        <span class="confirm-value" id="c-date">—</span>
                    </div>
                    <div class="confirm-row">
                        <span class="confirm-label">ساعت</span>
                        <span class="confirm-value" id="c-time">—</span>
                    </div>
                    <div class="confirm-row">
                        <span class="confirm-label">نوع مشاوره</span>
                        <span class="confirm-value">حضوری</span>
                    </div>
                    <div class="confirm-row">
                        <span class="confirm-label">هزینه</span>
                        <span class="confirm-value" style="color:var(--pgold-d);">{{ number_format($appointmentPrice) }} تومان</span>
                    </div>
                </div>

                <div style="background:#eff6ff;border-radius:10px;padding:15px 18px;margin-bottom:20px;font-size:0.85rem;color:#1e40af;border:1px solid #bfdbfe;">
                    <i class="fas fa-info-circle" style="margin-left:6px;"></i>
                    پس از تأیید به درگاه پرداخت زرین‌پال منتقل می‌شوید. نوبت پس از پرداخت موفق تأیید می‌گردد.
                </div>

                <label class="terms-check">
                    <input type="checkbox" id="terms-check" onchange="document.getElementById('submit-btn').disabled = !this.checked">
                    <span>قوانین و مقررات استفاده از خدمات مشاوره را مطالعه کرده و می‌پذیرم.</span>
                </label>

                <form action="{{ route('reserve.store') }}" method="POST" id="reserve-form">
                    @csrf
                    <input type="hidden" name="selected_date" id="input-date">
                    <input type="hidden" name="selected_time" id="input-time">
                    <input type="hidden" name="lawyer_id"     value="{{ $lawyer->id }}">

                    <button type="submit" id="submit-btn" class="btn-reserve-final" disabled>
                        <i class="fas fa-external-link-alt"></i>
                        تأیید و انتقال به درگاه پرداخت
                    </button>
                </form>

                <button class="btn-back-step" onclick="goStep(2)">
                    <i class="fas fa-arrow-right"></i> تغییر ساعت
                </button>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="booking-sidebar">
            <div class="sidebar-sticky">
                <div class="summary-card">
                    <div class="lawyer-mini">
                        @if($lawyer->image)
                            <img src="{{ $lawyer->image_url }}" alt="{{ $lawyer->name }}">
                        @else
                            <div class="lawyer-mini-placeholder">{{ mb_substr($lawyer->name, 0, 1) }}</div>
                        @endif
                        <div>
                            <h5>{{ $lawyer->name }}</h5>
                            <small>وکیل پایه {{ $lawyer->license_grade }} دادگستری</small>
                        </div>
                    </div>

                    <div class="sum-row">
                        <span class="sum-label">تاریخ رزرو</span>
                        <span class="sum-value pending" id="s-date">انتخاب نشده</span>
                    </div>
                    <div class="sum-row">
                        <span class="sum-label">ساعت</span>
                        <span class="sum-value pending" id="s-time">انتخاب نشده</span>
                    </div>
                    <div class="sum-row">
                        <span class="sum-label">نوع مشاوره</span>
                        <span class="sum-value">حضوری</span>
                    </div>

                    <div class="price-box">
                        <small>هزینه مشاوره</small>
                        <strong>{{ number_format($appointmentPrice) }} تومان</strong>
                    </div>
                </div>

                <div class="help-box">
                    <p>سوال یا مشکل دارید؟</p>
                    <a href="{{ route('contact') }}">
                        <i class="fas fa-phone"></i> تماس با دفتر
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
let selDate = '', selDateLabel = '', selTime = '';

// ─── Day Select ───────────────────────────────────────────────
function selectDay(el, date, label) {
    document.querySelectorAll('.cal-day').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
    selDate = date;
    selDateLabel = label;
    document.getElementById('input-date').value = date;
    document.getElementById('s-date').textContent = label;
    document.getElementById('s-date').classList.remove('pending');
    document.getElementById('slots-day-label').textContent = 'ساعت‌های موجود برای ' + label;
    document.getElementById('c-date').textContent = label;
    loadSlots(date);
    goStep(2);
}

// ─── Load Slots ───────────────────────────────────────────────
function loadSlots(date) {
    const container = document.getElementById('slots-container');
    const loader    = document.getElementById('slots-loader');
    container.innerHTML = '';
    loader.style.display = 'block';

    const url = `{{ route('reserve.slots') }}?date=${date}&lawyer_id={{ $lawyer->id }}`;

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(async res => {
            if (!res.ok) throw new Error('خطای سرور ' + res.status);
            return res.json();
        })
        .then(data => {
            loader.style.display = 'none';
            if (!data.success || !data.slots || data.slots.length === 0) {
                container.innerHTML = `
                    <div style="grid-column:1/-1;text-align:center;padding:30px;color:#aaa;">
                        <i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                        <p>هیچ ساعت آزادی برای این روز وجود ندارد</p>
                        <button class="btn-back-step" onclick="goStep(1)" style="margin:10px auto;">
                            <i class="fas fa-arrow-right"></i> انتخاب روز دیگر
                        </button>
                    </div>`;
                return;
            }
            data.slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.className = 'slot-btn';
                btn.type = 'button';
                btn.innerHTML = slot.start_time + '<small>' + slot.end_time + '</small>';
                btn.onclick = () => selectTime(btn, slot.start_time);
                container.appendChild(btn);
            });
        })
        .catch(err => {
            loader.style.display = 'none';
            container.innerHTML = `
                <div style="grid-column:1/-1;text-align:center;padding:30px;color:#e74c3c;">
                    <i class="fas fa-exclamation-triangle" style="font-size:1.5rem;display:block;margin-bottom:10px;"></i>
                    <p>خطا در دریافت ساعت‌ها. لطفاً صفحه را رفرش کنید.</p>
                </div>`;
            console.error(err);
        });
}

// ─── Time Select ──────────────────────────────────────────────
function selectTime(el, time) {
    document.querySelectorAll('.slot-btn').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    selTime = time;
    document.getElementById('input-time').value = time;
    document.getElementById('s-time').textContent = time;
    document.getElementById('s-time').classList.remove('pending');
    document.getElementById('c-time').textContent = time;
    setTimeout(() => goStep(3), 350);
}

// ─── Step Navigation ──────────────────────────────────────────
function goStep(n) {
    [1,2,3].forEach(i => {
        document.getElementById('step-' + i).style.display = i === n ? 'block' : 'none';
        const h = document.getElementById('step-h' + i);
        h.classList.remove('active', 'completed');
        if (i < n)  h.classList.add('completed');
        if (i === n) h.classList.add('active');
    });
    window.scrollTo({ top: document.querySelector('.reserve-section').offsetTop - 20, behavior: 'smooth' });
}
</script>
@endpush