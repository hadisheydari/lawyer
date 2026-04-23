@extends('layouts.public')

@section('title', 'رزرو نوبت مشاوره | دفتر وکالت ابدالی و جوشقانی')

@push('styles')
    <style>
        :root {
            --primary-navy: #102a43;
            --primary-gold: #c5a059;
            --gold-light: #e6cfa3;
            --gold-dark: #9e7f41;
            --bg-glass: rgba(255, 255, 255, 0.9);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reserve-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #fdfbf7 0%, #f5f0ea 100%);
            min-height: 80vh;
        }

        .booking-wrapper {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* --- Progress Steps --- */
        .steps-container {
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            margin-bottom: 50px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
            max-width: 200px;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 25px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #ddd;
            z-index: 1;
        }

        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #999;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            transition: var(--transition);
        }

        .step-item.active .step-number {
            border-color: var(--primary-gold);
            background: var(--primary-navy);
            color: #fff;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.3);
        }

        .step-item.completed .step-number {
            background: var(--primary-gold);
            border-color: var(--primary-gold);
            color: #fff;
        }

        .step-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #777;
        }

        /* --- Main Cards --- */
        .booking-card {
            background: var(--bg-glass);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(207, 168, 110, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--primary-navy);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title i {
            color: var(--primary-gold);
        }

        /* --- Calendar Style --- */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar-header-day {
            text-align: center;
            font-weight: 800;
            padding: 10px;
            color: var(--primary-navy);
            font-size: 0.9rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            background: #fff;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 1px solid #eee;
            transition: var(--transition);
            position: relative;
        }

        .calendar-day:hover:not(.disabled) {
            border-color: var(--primary-gold);
            transform: translateY(-3px);
        }

        .calendar-day.active {
            background: var(--primary-navy);
            color: #fff;
            border-color: var(--primary-navy);
        }

        .calendar-day.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            background: #f9f9f9;
        }

        .calendar-day.has-booked::after {
            content: '';
            position: absolute;
            bottom: 8px;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--primary-gold);
        }

        /* --- Time Slots --- */
        .slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 15px;
        }

        .slot-item {
            padding: 15px;
            background: #fff;
            border: 2px solid #eee;
            border-radius: 12px;
            text-align: center;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .slot-item:hover {
            border-color: var(--primary-gold);
            color: var(--primary-gold);
        }

        .slot-item.selected {
            background: var(--primary-gold);
            color: #fff;
            border-color: var(--primary-gold);
        }

        /* --- Sidebar Summary --- */
        .sidebar-sticky {
            position: sticky;
            top: 100px;
        }

        .summary-card {
            background: var(--primary-navy);
            color: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(16, 42, 67, 0.2);
        }

        .lawyer-mini-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .lawyer-mini-info img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid var(--primary-gold);
            object-fit: cover;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .info-label {
            color: rgba(255, 255, 255, 0.6);
        }

        .info-value {
            font-weight: 700;
            color: var(--gold-light);
        }

        .total-price {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-top: 20px;
        }

        .total-price span {
            font-size: 1.2rem;
            font-weight: 900;
            color: #fff;
        }

        .btn-reserve {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-gold), var(--gold-dark));
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-weight: 800;
            margin-top: 20px;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-reserve:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.4);
        }

        .btn-reserve:disabled {
            opacity: 0.5;
            filter: grayscale(1);
            cursor: not-allowed;
        }

        @media (max-width: 992px) {
            .booking-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="reserve-section">
        <div class="booking-wrapper">

            <div class="steps-container">
                <div class="step-item active" id="step-header-1">
                    <div class="step-number">۱</div>
                    <div class="step-label">انتخاب روز</div>
                </div>
                <div class="step-item" id="step-header-2">
                    <div class="step-number">۲</div>
                    <div class="step-label">انتخاب ساعت</div>
                </div>
                <div class="step-item" id="step-header-3">
                    <div class="step-number">۳</div>
                    <div class="step-label">تأیید نهایی</div>
                </div>
            </div>

            <div class="booking-main">

                <div class="booking-card" id="step-1">
                    <h3 class="card-title"><i class="fas fa-calendar-alt"></i> تقویم مشاوره</h3>

                    <div class="calendar-nav d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route('reserve.index', ['lawyer' => $lawyer->slug, 'month' => $calendar['prev_month'], 'year' => $calendar['prev_year']]) }}"
                            class="btn btn-sm btn-outline-secondary">ماه قبل <i class="fas fa-chevron-right"></i></a>
                        <h5 class="fw-bold m-0" style="color: var(--primary-navy)">
                            @php
                                $months = [
                                    'فروردین',
                                    'اردیبهشت',
                                    'خرداد',
                                    'تیر',
                                    'مرداد',
                                    'شهریور',
                                    'مهر',
                                    'آبان',
                                    'آذر',
                                    'دی',
                                    'بهمن',
                                    'اسفند',
                                ];
                            @endphp
                            {{ $months[$currentMonth - 1] }} {{ $currentYear }}
                        </h5>
                        <a href="{{ route('reserve.index', ['lawyer' => $lawyer->slug, 'month' => $calendar['next_month'], 'year' => $calendar['next_year']]) }}"
                            class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-left"></i> ماه بعد</a>
                    </div>

                    <div class="calendar-grid">
                        @foreach (['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] as $dayName)
                            <div class="calendar-header-day">{{ $dayName }}</div>
                        @endforeach

                        @for ($i = 0; $i < $calendar['start_day_of_week']; $i++)
                            <div class="calendar-day empty border-0"></div>
                        @endfor

                            @for ($day = 1; $day <= $calendar['days_in_month']; $day++)
                                @php
                                    // پرانتز ( قبل از new و پرانتز ) قبل از ->toCarbon اضافه شده است
                                    $gregorianDate = (new \Morilog\Jalali\Jalalian($currentYear, $currentMonth, $day))->toCarbon()->format('Y-m-d');

                                    $isFriday = ($calendar['start_day_of_week'] + $day - 1) % 7 == 6;

                                    $hasBooked = isset($calendar['booked_dates'][$gregorianDate]);
                                @endphp
                                <div class="calendar-day {{ $isFriday ? 'disabled' : '' }} {{ $hasBooked ? 'has-booked' : '' }}"
                                    onclick="selectDate(this, '{{ $gregorianDate }}', '{{ $day }} {{ $months[$currentMonth - 1] }}')">
                                    <span class="fw-bold">{{ $day }}</span>
                                </div>
                            @endfor
                    </div>
                </div>

                <div class="booking-card d-none" id="step-2">
                    <h3 class="card-title"><i class="fas fa-clock"></i> ساعت‌های موجود</h3>
                    <p id="selected-day-text" class="text-muted mb-4"></p>

                    <div id="slots-loader" class="text-center py-5 d-none">
                        <div class="spinner-border text-gold" role="status"></div>
                    </div>

                    <div class="slots-grid" id="slots-container">
                    </div>

                    <button class="btn btn-link mt-4 text-decoration-none p-0" onclick="goToStep(1)">
                        <i class="fas fa-arrow-right"></i> تغییر روز
                    </button>
                </div>

                <div class="booking-card d-none" id="step-3">
                    <h3 class="card-title"><i class="fas fa-check-circle"></i> تأیید نهایی و پرداخت</h3>

                    <div class="alert alert-info border-0 rounded-4 p-4">
                        <ul class="m-0 ps-3">
                            <li>لطفاً ۵ دقیقه قبل از زمان مقرر در سایت حاضر باشید.</li>
                            <li>امکان لغو نوبت تا ۲۴ ساعت قبل مقدور است.</li>
                        </ul>
                    </div>

                    <div class="form-check my-4">
                        <input class="form-check-input" type="checkbox" id="terms"
                            onchange="document.getElementById('submit-btn').disabled = !this.checked">
                        <label class="form-check-label fw-bold" for="terms">
                            قوانین و مقررات را مطالعه کرده و می‌پذیرم.
                        </label>
                    </div>

                    <form action="{{ route('reserve.store') }}" method="POST" id="main-reserve-form">
                        @csrf
                        <input type="hidden" name="selected_date" id="input-date">
                        <input type="hidden" name="selected_time" id="input-time">
                        <input type="hidden" name="lawyer_id" value="{{ $lawyer->id }}">

                        <button type="submit" id="submit-btn" class="btn-reserve" disabled>
                            تأیید و انتقال به درگاه پرداخت <i class="fas fa-external-link-alt ms-2"></i>
                        </button>
                    </form>
                </div>

            </div>

            <div class="booking-sidebar">
                <div class="sidebar-sticky">
                    <div class="summary-card">
                        <div class="lawyer-mini-info">
                            <img src="{{ $lawyer->image_url }}" alt="{{ $lawyer->name }}">
                            <div>
                                <h6 class="m-0 fw-bold">{{ $lawyer->name }}</h6>
                                <small class="text-white-50">وکیل پایه یک دادگستری</small>
                            </div>
                        </div>

                        <div class="info-row">
                            <span class="info-label">تاریخ رزرو:</span>
                            <span class="info-value" id="summary-date">---</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ساعت:</span>
                            <span class="info-value" id="summary-time">---</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">نوع مشاوره:</span>
                            <span class="info-value">حضوری</span>
                        </div>

                        <div class="total-price">
                            <p class="small text-white-50 mb-1">هزینه مشاوره</p>
                            <span>{{ number_format($appointmentPrice) }} تومان</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let selectedDate = '';
        let selectedTime = '';

        function selectDate(element, date, dateLabel) {
            if (element.classList.contains('disabled')) return;

            // UI Changes
            document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('active'));
            element.classList.add('active');

            selectedDate = date;
            document.getElementById('input-date').value = date;
            document.getElementById('summary-date').innerText = dateLabel;
            document.getElementById('selected-day-text').innerText = 'ساعت‌های موجود برای ' + dateLabel;

            // Load Slots
            loadSlots(date);
            goToStep(2);
        }

        function loadSlots(date) {
            const container = document.getElementById('slots-container');
            const loader = document.getElementById('slots-loader');

            container.innerHTML = '';
            loader.classList.remove('d-none');

            // ساخت آدرس درخواست
            const url = `{{ route('reserve.slots') }}?date=${date}&lawyer_id={{ $lawyer->id }}`;
            console.log("در حال ارسال درخواست به:", url);

            fetch(url)
                .then(async (res) => {
                    // اگر سرور ارور 500 یا 404 داد، متن ارور را بگیر تا در کنسول ببینیم
                    if (!res.ok) {
                        const text = await res.text();
                        throw new Error(`خطای سرور: ${res.status} - ${text}`);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log("پاسخ موفق از سرور:", data);
                    loader.classList.add('d-none');

                    // اگر موفق نبود یا آرایه خالی بود
                    if (!data.success || !data.slots || data.slots.length === 0) {
                        container.innerHTML =
                            '<p class="text-danger fw-bold mt-3">متأسفانه هیچ ساعتی برای این روز موجود نیست.</p>';
                        return;
                    }

                    // ساخت دکمه‌های ساعت
                    data.slots.forEach(slot => {
                        const div = document.createElement('div');
                        div.className = 'slot-item';
                        div.innerText = slot.start_time;
                        div.onclick = () => selectTime(div, slot.start_time);
                        container.appendChild(div);
                    });
                })
                .catch(error => {
                    // نمایش ارور در صورت قطعی یا خطای بک‌اند
                    console.error("گزارش خطای کامل:", error);
                    loader.classList.add('d-none');
                    container.innerHTML =
                        '<p class="text-danger fw-bold mt-3">خطا در دریافت اطلاعات. لطفاً کلید F12 را بزنید و تب Console را چک کنید.</p>';
                });
        }

        function selectTime(element, time) {
            document.querySelectorAll('.slot-item').forEach(s => s.classList.remove('selected'));
            element.classList.add('selected');

            selectedTime = time;
            document.getElementById('input-time').value = time;
            document.getElementById('summary-time').innerText = time;

            setTimeout(() => goToStep(3), 400);
        }

        function goToStep(stepNumber) {
            // Content
            document.getElementById('step-1').classList.add('d-none');
            document.getElementById('step-2').classList.add('d-none');
            document.getElementById('step-3').classList.add('d-none');
            document.getElementById('step-' + stepNumber).classList.remove('d-none');

            // Header
            document.querySelectorAll('.step-item').forEach((h, index) => {
                h.classList.remove('active', 'completed');
                if (index + 1 < stepNumber) h.classList.add('completed');
                if (index + 1 === stepNumber) h.classList.add('active');
            });
        }
    </script>
@endpush
