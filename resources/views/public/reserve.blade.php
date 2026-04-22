@extends('layouts.app')

@section('title', 'رزرو نوبت مشاوره')

@section('content')
<div class="reserve-container">
    <div class="reserve-header">
        <h1>رزرو نوبت مشاوره</h1>
        @if($selectedLawyer)
            <div class="lawyer-info">
                <img src="{{ $selectedLawyer->avatar ?? '/images/default-avatar.png' }}" alt="{{ $selectedLawyer->name }}">
                <div>
                    <h3>{{ $selectedLawyer->name }}</h3>
                    <p>{{ $selectedLawyer->specialty }}</p>
                </div>
            </div>
        @endif
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="reserve-steps">
        <div class="step active" data-step="1">
            <span class="step-number">1</span>
            <span class="step-label">انتخاب تاریخ</span>
        </div>
        <div class="step" data-step="2">
            <span class="step-number">2</span>
            <span class="step-label">انتخاب ساعت</span>
        </div>
        <div class="step" data-step="3">
            <span class="step-number">3</span>
            <span class="step-label">تایید و پرداخت</span>
        </div>
    </div>

    <div class="reserve-content">
        <!-- مرحله 1: انتخاب تاریخ -->
        <div class="step-content active" id="step1">
            <div class="calendar-wrapper">
                <div class="calendar-header">
                    <button type="button" class="nav-btn" onclick="changeMonth(-1)">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/>
                        </svg>
                    </button>
                    <h2>{{ $monthName }} {{ $year }}</h2>
                    <button type="button" class="nav-btn" onclick="changeMonth(1)">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                    </button>
                </div>

                <div class="calendar-grid">
                    <div class="day-name">ش</div>
                    <div class="day-name">ی</div>
                    <div class="day-name">د</div>
                    <div class="day-name">س</div>
                    <div class="day-name">چ</div>
                    <div class="day-name">پ</div>
                    <div class="day-name">ج</div>

                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                        <div class="day empty"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $isPast = ($year < $nowYear) || ($year == $nowYear && $month < $nowMonth) || ($year == $nowYear && $month == $nowMonth && $day < $nowDay);
                            $isFriday = (($firstDayOfWeek + $day - 1) % 7) == 6;
                            $isBooked = in_array($day, $bookedDates);
                            $isDisabled = $isPast || $isFriday;
                        @endphp
                        <button 
                            type="button"
                            class="day {{ $isDisabled ? 'disabled' : '' }} {{ $isBooked ? 'booked' : '' }}"
                            data-date="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}"
                            {{ $isDisabled ? 'disabled' : '' }}
                            onclick="selectDate(this)"
                        >
                            {{ $day }}
                        </button>
                    @endfor
                </div>

                <div class="calendar-legend">
                    <div class="legend-item">
                        <span class="legend-color available"></span>
                        <span>روز آزاد</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color booked"></span>
                        <span>رزرو شده</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color disabled"></span>
                        <span>غیرفعال</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- مرحله 2: انتخاب ساعت -->
        <div class="step-content" id="step2">
            <div class="selected-date-display">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                </svg>
                <span id="selectedDateText"></span>
            </div>

            <div class="slots-loading" id="slotsLoading">
                <div class="spinner"></div>
                <p>در حال بارگذاری ساعت‌های موجود...</p>
            </div>

            <div class="slots-container" id="slotsContainer"></div>

            <div class="slots-empty" id="slotsEmpty" style="display: none;">
                <svg width="48" height="48" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <p>متأسفانه ساعت خالی برای این روز وجود ندارد</p>
                <button type="button" class="btn-secondary" onclick="goToStep(1)">انتخاب روز دیگر</button>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-secondary" onclick="goToStep(1)">بازگشت</button>
            </div>
        </div>

        <!-- مرحله 3: تایید -->
        <div class="step-content" id="step3">
            <div class="summary-card">
                <h3>خلاصه رزرو شما</h3>
                
                <div class="summary-row">
                    <span class="summary-label">تاریخ:</span>
                    <span class="summary-value" id="summaryDate"></span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">ساعت:</span>
                    <span class="summary-value" id="summaryTime"></span>
                </div>

                @if($selectedLawyer)
                <div class="summary-row">
                    <span class="summary-label">وکیل:</span>
                    <span class="summary-value">{{ $selectedLawyer->name }}</span>
                </div>
                @endif

                <div class="summary-row total">
                    <span class="summary-label">مبلغ قابل پرداخت:</span>
                    <span class="summary-value">{{ number_format($selectedLawyer->consultation_price ?? 50000) }} تومان</span>
                </div>
            </div>

            <form method="POST" action="{{ route('reserve.store') }}" id="reserveForm">
                @csrf
                <input type="hidden" name="selected_date" id="selectedDateInput">
                <input type="hidden" name="selected_time" id="selectedTimeInput">
                @if($selectedLawyer)
                    <input type="hidden" name="lawyer_id" value="{{ $selectedLawyer->id }}">
                @endif

                <div class="step-actions">
                    <button type="button" class="btn-secondary" onclick="goToStep(2)">بازگشت</button>
                    <button type="submit" class="btn-primary" id="submitBtn">
                        <span class="btn-text">پرداخت و ثبت نوبت</span>
                        <span class="btn-loading" style="display: none;">
                            <span class="spinner-small"></span>
                            در حال انتقال...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.reserve-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.reserve-header {
    text-align: center;
    margin-bottom: 2rem;
}

.reserve-header h1 {
    font-size: 1.75rem;
    color: #1a202c;
    margin-bottom: 1rem;
}

.lawyer-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 12px;
}

.lawyer-info img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.lawyer-info h3 {
    font-size: 1.125rem;
    color: #2d3748;
    margin: 0;
}

.lawyer-info p {
    font-size: 0.875rem;
    color: #718096;
    margin: 0.25rem 0 0;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-error {
    background: #fff5f5;
    color: #c53030;
    border: 1px solid #feb2b2;
}

.reserve-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
}

.reserve-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 25%;
    right: 25%;
    height: 2px;
    background: #e2e8f0;
    z-index: 0;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    z-index: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #a0aec0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: all 0.3s;
}

.step.active .step-number {
    background: #3182ce;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: #718096;
}

.step.active .step-label {
    color: #2d3748;
    font-weight: 500;
}

.reserve-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 2rem;
}

.step-content {
    display: none;
}

.step-content.active {
    display: block;
}

.calendar-wrapper {
    max-width: 500px;
    margin: 0 auto;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.calendar-header h2 {
    font-size: 1.25rem;
    color: #2d3748;
}

.nav-btn {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.nav-btn:hover {
    background: #edf2f7;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.5rem;
}

.day-name {
    text-align: center;
    font-size: 0.875rem;
    font-weight: 600;
    color: #4a5568;
    padding: 0.5rem;
}

.day {
    aspect-ratio: 1;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.day:not(.disabled):not(.empty):hover {
    background: #ebf8ff;
    border-color: #3182ce;
}

.day.selected {
    background: #3182ce;
    color: white;
    border-color: #3182ce;
}

.day.disabled {
    background: #f7fafc;
    color: #cbd5e0;
    cursor: not-allowed;
}

.day.booked {
    background: #fef5e7;
    border-color: #f6ad55;
}

.day.empty {
    border: none;
    cursor: default;
}

.calendar-legend {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #4a5568;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
}

.legend-color.available {
    background: white;
}

.legend-color.booked {
    background: #fef5e7;
    border-color: #f6ad55;
}

.legend-color.disabled {
    background: #f7fafc;
}

.selected-date-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: #ebf8ff;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    color: #2c5282;
    font-weight: 500;
}

.slots-loading {
    text-align: center;
    padding: 3rem 1rem;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e2e8f0;
    border-top-color: #3182ce;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.slots-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.75rem;
}

.slot-btn {
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
    font-size: 0.875rem;
}

.slot-btn:hover {
    border-color: #3182ce;
    background: #ebf8ff;
}

.slot-btn.selected {
    background: #3182ce;
    color: white;
    border-color: #3182ce;
}

.slots-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #718096;
}

.slots-empty svg {
    margin: 0 auto 1rem;
    opacity: 0.5;
}

.summary-card {
    background: #f7fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.summary-card h3 {
    font-size: 1.125rem;
    color: #2d3748;
    margin-bottom: 1rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row.total {
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-top: 2px solid #cbd5e0;
    font-weight: 600;
    font-size: 1.125rem;
}

.summary-label {
    color: #718096;
}

.summary-value {
    color: #2d3748;
    font-weight: 500;
}

.step-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    font-size: 1rem;
}

.btn-primary {
    background: #3182ce;
    color: white;
}

.btn-primary:hover {
    background: #2c5282;
}

.btn-primary:disabled {
    background: #cbd5e0;
    cursor: not-allowed;
}

.btn-secondary {
    background: white;
    color: #4a5568;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #f7fafc;
}

.btn-loading {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.spinner-small {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@media (max-width: 640px) {
    .reserve-container {
        padding: 0 0.5rem;
    }
    
    .reserve-content {
        padding: 1rem;
    }
    
    .calendar-grid {
        gap: 0.25rem;
    }
    
    .slots-container {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
    
    .step-actions {
        flex-direction: column-reverse;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
    }
}
</style>

<script>
let currentStep = 1;
let selectedDate = null;
let selectedTime = null;
let selectedSlotDisplay = null;

function goToStep(step) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.step-content').forEach(s => s.classList.remove('active'));
    
    document.querySelector(`.step[data-step="${step}"]`).classList.add('active');
    document.getElementById(`step${step}`).classList.add('active');
    
    currentStep = step;
}

function selectDate(el) {
    if (el.classList.contains('disabled')) return;
    
    document.querySelectorAll('.day').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
    
    selectedDate = el.dataset.date;
    
    loadTimeSlots(selectedDate);
    goToStep(2);
}

function loadTimeSlots(date) {
    const container = document.getElementById('slotsContainer');
    const loading = document.getElementById('slotsLoading');
    const empty = document.getElementById('slotsEmpty');
    const dateDisplay = document.getElementById('selectedDateText');
    
    container.innerHTML = '';
    container.style.display = 'none';
    empty.style.display = 'none';
    loading.style.display = 'block';
    
    const jDate = new Date(date).toLocaleDateString('fa-IR');
    dateDisplay.textContent = jDate;
    
    const lawyerId = {{ $selectedLawyer->id ?? 'null' }};
    
    fetch(`/reserve/slots?date=${date}&lawyer_id=${lawyerId}`)
        .then(res => res.json())
        .then(data => {
            loading.style.display = 'none';
            
            if (data.slots && data.slots.length > 0) {
                container.style.display = 'grid';
                data.slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'slot-btn';
                    btn.textContent = slot.display;
                    btn.onclick = () => selectSlot(btn, slot);
                    container.appendChild(btn);
                });
            } else {
                empty.style.display = 'block';
            }
        })
        .catch(err => {
            loading.style.display = 'none';
            empty.style.display = 'block';
            console.error(err);
        });
}

function selectSlot(el, slot) {
    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    
    selectedTime = slot.start_time;
    selectedSlotDisplay = slot.display;
    
    document.getElementById('selectedDateInput').value = selectedDate;
    document.getElementById('selectedTimeInput').value = selectedTime;
    document.getElementById('summaryDate').textContent = document.getElementById('selectedDateText').textContent;
    document.getElementById('summaryTime').textContent = selectedSlotDisplay;
    
    goToStep(3);
}

function changeMonth(delta) {
    const params = new URLSearchParams(window.location.search);
    let year = parseInt(params.get('year') || {{ $year }});
    let month = parseInt(params.get('month') || {{ $month }});
    
    month += delta;
    if (month > 12) {
        month = 1;
        year++;
    } else if (month < 1) {
        month = 12;
        year--;
    }
    
    const now = new Date();
    const nowJalali = new Date(now).toLocaleDateString('fa-IR-u-nu-latn').split('/');
    const nowYear = parseInt(nowJalali[0]);
    const nowMonth = parseInt(nowJalali[1]);
    
    if (year < nowYear || (year === nowYear && month < nowMonth)) {
        return;
    }
    
    params.set('year', year);
    params.set('month', month);
    window.location.search = params.toString();
}

document.getElementById('reserveForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.querySelector('.btn-text').style.display = 'none';
    btn.querySelector('.btn-loading').style.display = 'flex';
});
</script>
@endsection
