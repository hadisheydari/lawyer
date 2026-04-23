@extends('layouts.app')

@section('title', 'رزرو نوبت مشاوره')

@section('styles')
<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #dbeafe;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Vazirmatn', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    .reserve-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .reserve-header {
        text-align: center;
        color: white;
        margin-bottom: 3rem;
        animation: fadeInDown 0.6s ease;
    }

    .reserve-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .reserve-header p {
        font-size: 1.125rem;
        opacity: 0.95;
    }

    .reserve-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    .main-card {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        animation: fadeInUp 0.6s ease;
    }

    .sidebar-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        position: sticky;
        top: 2rem;
        animation: fadeInUp 0.6s ease 0.2s both;
    }

    /* Progress Steps */
    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        right: 0;
        left: 0;
        height: 2px;
        background: var(--gray-200);
        z-index: 0;
    }

    .progress-line {
        position: absolute;
        top: 20px;
        right: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.4s ease;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--gray-400);
        transition: all 0.3s ease;
    }

    .step.active .step-circle {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    .step.completed .step-circle {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-500);
        transition: color 0.3s ease;
    }

    .step.active .step-label {
        color: var(--primary);
    }

    .step.completed .step-label {
        color: var(--success);
    }

    /* Step Content */
    .step-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .step-content.active {
        display: block;
    }

    .step-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .step-title svg {
        width: 28px;
        height: 28px;
        color: var(--primary);
    }

    /* Calendar */
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 12px;
    }

    .calendar-nav {
        display: flex;
        gap: 0.5rem;
    }

    .calendar-nav button {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        background: white;
        color: var(--gray-700);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }

    .calendar-nav button:hover:not(:disabled) {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .calendar-nav button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .calendar-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--gray-800);
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .weekday {
        text-align: center;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        padding: 0.75rem;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }

    .day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        background: var(--gray-50);
        color: var(--gray-700);
    }

    .day:hover:not(.disabled):not(.empty) {
        background: var(--primary-light);
        color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .day.selected {
        background: var(--primary);
        color: white;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        transform: scale(1.05);
    }

    .day.today {
        border: 2px solid var(--primary);
        font-weight: 700;
    }

    .day.disabled {
        background: transparent;
        color: var(--gray-300);
        cursor: not-allowed;
    }

    .day.friday {
        background: #fee2e2;
        color: #991b1b;
        cursor: not-allowed;
    }

    .day.booked::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--warning);
    }

    .day.empty {
        background: transparent;
        cursor: default;
    }

    /* Time Slots */
    .time-slots-container {
        max-height: 500px;
        overflow-y: auto;
        padding: 0.5rem;
    }

    .time-slots-container::-webkit-scrollbar {
        width: 6px;
    }

    .time-slots-container::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 10px;
    }

    .time-slots-container::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 10px;
    }

    .time-slots-container::-webkit-scrollbar-thumb:hover {
        background: var(--gray-400);
    }

    .time-slots {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
    }

    .time-slot {
        padding: 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
        font-weight: 600;
        color: var(--gray-700);
    }

    .time-slot:hover {
        border-color: var(--primary);
        background: var(--primary-light);
        color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .time-slot.selected {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .loading-state,
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--gray-500);
    }

    .loading-state svg,
    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        opacity: 0.5;
    }

    .loading-state svg {
        animation: spin 1s linear infinite;
    }

    /* Summary Card */
    .summary-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }

    .summary-title {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .summary-value {
        font-weight: 700;
        font-size: 1rem;
    }

    .price-highlight {
        background: rgba(255,255,255,0.2);
        padding: 1rem;
        border-radius: 12px;
        margin-top: 1rem;
        text-align: center;
    }

    .price-label {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-bottom: 0.25rem;
    }

    .price-value {
        font-size: 1.75rem;
        font-weight: 700;
    }

    /* Lawyer Info */
    .lawyer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: var(--gray-50);
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    .lawyer-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .lawyer-details h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
    }

    .lawyer-details p {
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .lawyer-rating {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }

    .lawyer-rating svg {
        width: 16px;
        height: 16px;
        color: #fbbf24;
    }

    /* Buttons */
    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        flex: 1;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .btn-primary:hover:not(:disabled) {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
    }

    .btn-secondary:hover {
        background: var(--gray-200);
        transform: translateY(-2px);
    }

    .btn-success {
        background: var(--success);
        color: white;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .btn-success:hover:not(:disabled) {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    /* Alert Messages */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideInDown 0.3s ease;
    }

    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    /* Confirmation Details */
    .confirmation-details {
        background: var(--gray-50);
        border-radius: 16px;
        padding: 2rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.875rem;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-label svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }

    .detail-value {
        font-weight: 700;
        color: var(--gray-900);
        font-size: 1rem;
    }

    .terms-checkbox {
        display: flex;
        align-items: start;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding: 1rem;
        background: white;
        border-radius: 12px;
    }

    .terms-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        margin-top: 2px;
    }

    .terms-checkbox label {
        font-size: 0.875rem;
        color: var(--gray-700);
        cursor: pointer;
        line-height: 1.6;
    }

    .terms-checkbox a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    .terms-checkbox a:hover {
        text-decoration: underline;
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .reserve-content {
            grid-template-columns: 1fr;
        }

        .sidebar-card {
            position: static;
        }
    }

    @media (max-width: 640px) {
        body {
            padding: 1rem 0.5rem;
        }

        .reserve-header h1 {
            font-size: 1.75rem;
        }

        .reserve-header p {
            font-size: 1rem;
        }

        .main-card,
        .sidebar-card {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .step-label {
            font-size: 0.75rem;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            font-size: 0.875rem;
        }

        .calendar-days {
            gap: 0.25rem;
        }

        .day {
            font-size: 0.875rem;
            border-radius: 8px;
        }

        .time-slots {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.5rem;
        }

        .btn-group {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="reserve-container">
    <!-- Header -->
    <div class="reserve-header">
        <h1>رزرو نوبت مشاوره حقوقی</h1>
        <p>تاریخ و ساعت مناسب خود را انتخاب کنید</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ session('success') }}
                </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="reserve-content">
        <!-- Left (Main) -->
        <div class="main-card">
            <!-- Progress Steps -->
            <div class="progress-steps">
                <div class="step active">
                    <div class="step-circle">۱</div>
                    <div class="step-label">انتخاب تاریخ</div>
                </div>
                <div class="step">
                    <div class="step-circle">۲</div>
                    <div class="step-label">انتخاب ساعت</div>
                </div>
                <div class="step">
                    <div class="step-circle">۳</div>
                    <div class="step-label">تأیید و پرداخت</div>
                </div>
            </div>

            <!-- Step: Calendar -->
            <div class="step-content active" id="step-calendar">
                <div class="step-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18M5 19h14" />
                    </svg>
                    انتخاب تاریخ مشاوره
                </div>

                <div class="calendar-header">
                    <div class="calendar-nav">
                        <button id="prevMonth">‹</button>
                        <button id="nextMonth">›</button>
                    </div>
                    <div class="calendar-title" id="calendarTitle"></div>
                </div>

                <div class="calendar-weekdays">
                    <div class="weekday">شـ</div>
                    <div class="weekday">یـ</div>
                    <div class="weekday">د</div>
                    <div class="weekday">س</div>
                    <div class="weekday">چ</div>
                    <div class="weekday">پ</div>
                    <div class="weekday">ج</div>
                </div>

                <div class="calendar-days" id="calendarDays"></div>
            </div>

            <!-- Step: Time Slots -->
            <div class="step-content" id="step-times">
                <div class="step-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6 5H3V5h18v16z" />
                    </svg>
                    انتخاب ساعت مشاوره
                </div>

                <div class="loading-state" id="slotsLoading" style="display:none;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="4" />
                    </svg>
                    در حال بارگذاری ساعات...
                </div>

                <div class="empty-state" id="slotsEmpty" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 9l3 3-3 3M16 9l-3 3 3 3" />
                    </svg>
                    هیچ ساعت خالی‌ای برای این روز وجود ندارد.
                </div>

                <div class="time-slots-container">
                    <div class="time-slots" id="slotsContainer"></div>
                </div>
            </div>

            <!-- Step: Confirmation -->
            <div class="step-content" id="step-confirm">
                <div class="step-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7 20h10a2 2 0 002-2V8a2 2 0 00-2-2h-4l-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    تأیید نهایی و پرداخت
                </div>

                <div class="confirmation-details">
                    <div class="detail-row">
                        <div class="detail-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3M3 11h18" />
                            </svg>
                            تاریخ انتخاب‌شده:
                        </div>
                        <div class="detail-value" id="selectedDateDisplay">---</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3" />
                            </svg>
                            ساعت انتخاب‌شده:
                        </div>
                        <div class="detail-value" id="selectedTimeDisplay">---</div>
                    </div>

                    <div class="terms-checkbox">
                        <input type="checkbox" id="acceptTerms" />
                        <label for="acceptTerms">
                            شرایط و <a href="#">قوانین استفاده از سامانه</a> را مطالعه کرده و قبول دارم.
                        </label>
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-secondary" id="backToTimes">
                        بازگشت
                    </button>
                    <form id="confirmForm" action="{{ route('reserve.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_date" id="dateInput" />
                        <input type="hidden" name="selected_time" id="timeInput" />
                        <input type="hidden" name="lawyer_id" value="{{ $lawyer->id }}" />
                        <button class="btn btn-success" id="finalSubmit" disabled>
                            پرداخت و رزرو نهایی
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar-card">
            <div class="summary-card">
                <div class="summary-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18" />
                    </svg>
                    خلاصه نوبت
                </div>
                <div class="summary-item">
                    <div class="summary-label">تاریخ:</div>
                    <div class="summary-value" id="summaryDate">---</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">ساعت:</div>
                    <div class="summary-value" id="summaryTime">---</div>
                </div>
                <div class="price-highlight">
                    <div class="price-label">مبلغ مشاوره</div>
                    <div class="price-value">{{ number_format($lawyer->consultation_price ?? 500000) }} تومان</div>
                </div>
            </div>

            <div class="lawyer-info">
                <img src="{{ $lawyer->avatar_url }}" alt="{{ $lawyer->name }}" class="lawyer-avatar" />
                <div class="lawyer-details">
                    <h3>{{ $lawyer->name }}</h3>
                    <p>{{ $lawyer->specialty }}</p>
                    <div class="lawyer-rating">
                        @for($i=0;$i<5;$i++)
                            <svg fill="{{ $i < $lawyer->rating ? '#fbbf24' : 'none' }}" stroke="#fbbf24"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 17l-5 3 2-5-4-4h6l2-5 2 5h6l-4 4 2 5z" />
                            </svg>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // نمونه ساده برای تعامل مراحل و فرم
    const stepCalendar = document.getElementById('step-calendar');
    const stepTimes = document.getElementById('step-times');
    const stepConfirm = document.getElementById('step-confirm');
    const finalSubmit = document.getElementById('finalSubmit');
    const acceptTerms = document.getElementById('acceptTerms');

    acceptTerms.addEventListener('change', e => {
        finalSubmit.disabled = !e.target.checked;
    });

    // اینجا می‌توانید کد AJAX برای لود روزها و ساعت‌ها اضافه کنید.
</script>
@endsection
