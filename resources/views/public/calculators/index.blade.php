@extends('layouts.public')
@section('title', 'محاسبات حقوقی | ابدالی و جوشقانی')

@push('styles')
    <style>
        :root {
            --gold-main: #d4af37;
            --gold-dark: #aa8222;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --shadow-card: 0 20px 40px -5px rgba(15, 23, 42, 0.05);
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .calc-page {
            max-width: 1150px;
            margin: 0 auto;
            padding: 70px 20px 100px;
        }

        /* ─── Tab Navigation ─────────────────────────────────────────── */
        .calc-tabs {
            display: flex;
            gap: 10px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(212, 175, 55, 0.15);
            margin-bottom: 40px;
            overflow-x: auto;
        }

        .calc-tab {
            flex: 1;
            padding: 16px 10px;
            border: none;
            background: transparent;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-body);
            cursor: pointer;
            border-radius: 14px;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            min-width: 130px;
        }

        .calc-tab i {
            font-size: 1.4rem;
            color: #94a3b8;
            transition: var(--transition);
        }

        .calc-tab:hover {
            background: #f8fafc;
            color: var(--navy);
        }

        .calc-tab:hover i {
            color: var(--gold-dark);
        }

        .calc-tab.active {
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            color: var(--gold-main);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
        }

        .calc-tab.active i {
            color: var(--gold-main);
        }

        /* ─── Calculator Panels ─────────────────────────────────────── */
        .calc-panel {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .calc-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .calc-card {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .calc-card-header {
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            padding: 40px 45px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 25px;
            position: relative;
            overflow: hidden;
        }

        .calc-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--gold-main), var(--gold-light));
        }

        .calc-card-header-icon {
            width: 75px;
            height: 75px;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--gold-main);
            flex-shrink: 0;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .calc-card-header h2 {
            font-size: 1.5rem;
            font-weight: 900;
            margin-bottom: 8px;
            color: #fff;
        }

        .calc-card-header p {
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .calc-body {
            padding: 50px 45px;
        }

        /* Grid ورودی‌ها و نتیجه */
        .calc-inner {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 50px;
            align-items: start;
        }

        .calc-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 10px;
        }

        .calc-label small {
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ─── Patch برای Select و پسوندها در Input-box ─── */
        .input-box select {
            flex: 1;
            border: none !important;
            background: transparent !important;
            padding: 15px 0;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-heading);
            outline: none !important;
            box-shadow: none !important;
            appearance: none;
            cursor: pointer;
        }

        .suffix-text {
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 800;
            padding-right: 15px;
            border-right: 1.5px solid #e2e8f0;
            margin-right: 10px;
        }

        .btn-calc {
            width: 100%;
            padding: 18px;
            margin-top: 15px;
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-family: 'Vazirmatn', sans-serif;
            font-size: 1.05rem;
            font-weight: 900;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 10px 25px -5px rgba(212, 175, 55, 0.5);
        }

        .btn-calc:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px -5px rgba(212, 175, 55, 0.7);
        }

        /* Result Box */
        .calc-result {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 24px;
            padding: 40px;
            border: 1px solid rgba(212, 175, 55, 0.2);
            min-height: 320px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }

        .calc-result::before {
            content: '§';
            position: absolute;
            left: -10px;
            bottom: -30px;
            font-size: 14rem;
            color: rgba(212, 175, 55, 0.04);
            font-family: serif;
            pointer-events: none;
            line-height: 1;
        }

        .result-empty {
            text-align: center;
            color: var(--text-body);
            font-size: 0.95rem;
            font-weight: 600;
        }

        .result-empty i {
            font-size: 3rem;
            color: var(--gold-main);
            margin-bottom: 15px;
            display: block;
            opacity: 0.3;
        }

        .result-show {
            display: none;
            position: relative;
            z-index: 2;
        }

        .result-tag {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--gold-dark);
            margin-bottom: 8px;
            background: rgba(212, 175, 55, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .result-main-num {
            font-size: clamp(2rem, 3vw, 2.5rem);
            font-weight: 900;
            color: var(--navy);
            line-height: 1.3;
            margin-bottom: 5px;
            margin-top: 10px;
        }

        .result-unit {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-body);
            margin-bottom: 30px;
        }

        .result-items {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.04);
            font-size: 0.9rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
        }

        .result-item .ri-label {
            color: var(--text-body);
            font-weight: 600;
        }

        .result-item .ri-val {
            font-weight: 900;
            color: var(--navy);
        }

        /* Info box */
        .calc-info {
            margin-top: 40px;
            padding: 25px 30px;
            background: #f8fafc;
            border-right: 4px solid var(--gold-main);
            border-radius: 0 16px 16px 0;
            font-size: 0.9rem;
            color: var(--text-body);
            line-height: 1.9;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .calc-info strong {
            color: var(--navy);
            font-weight: 800;
        }

        @media (max-width: 900px) {
            .calc-inner {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .calc-body {
                padding: 30px 20px;
            }

            .calc-card-header {
                padding: 30px 20px;
                flex-direction: column;
                text-align: center;
            }

            .calc-card-header-icon {
                margin: 0 auto;
            }

            .calc-tabs {
                gap: 5px;
                flex-wrap: wrap;
            }

            .calc-tab {
                font-size: 0.85rem;
                min-width: 48%;
                padding: 12px 5px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="page-banner" style="margin-right: 3%; margin-top: 3%; border-radius: 20px;">
        <div class="page-banner-inner">
            <h1><i class="fas fa-calculator" style="color:var(--gold-main);margin-left:15px;"></i>محاسبات حقوقی</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">صفحه اصلی</a>
                <i class="fas fa-chevron-left"></i>
                <span>ماشین‌حساب‌های حقوقی</span>
            </div>
        </div>
    </div>

    <div class="calc-page">

        {{-- ─── Tabs ──────────────────────────────────────────────── --}}
        <div class="calc-tabs">
            <button class="calc-tab active" onclick="switchTab(0)">
                <i class="fas fa-ring"></i> مهریه
            </button>
            <button class="calc-tab" onclick="switchTab(1)">
                <i class="fas fa-heartbeat"></i> دیه
            </button>
            <button class="calc-tab" onclick="switchTab(2)">
                <i class="fas fa-file-invoice-dollar"></i> هزینه دادرسی
            </button>
            <button class="calc-tab" onclick="switchTab(3)">
                <i class="fas fa-chart-line"></i> خسارت تأخیر
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════
         TAB 0: محاسبه مهریه
    ══════════════════════════════════════════════════════════ --}}
        <div class="calc-panel active" id="panel-0">
            <div class="calc-card">
                <div class="calc-card-header">
                    <div class="calc-card-header-icon"><i class="fas fa-coins"></i></div>
                    <div>
                        <h2>محاسبه مهریه به نرخ روز</h2>
                        <p>بر اساس شاخص بهای کالاها و خدمات — بانک مرکزی ایران</p>
                    </div>
                </div>
                <div class="calc-body">
                    <div class="calc-inner">
                        <div class="calc-inputs">

                            <div class="form-group-c">
                                <label class="calc-label">تعداد سکه طلا <small>(عدد)</small></label>
                                <div class="input-box">
                                    <i class="fas fa-coins"></i>
                                    <input type="number" id="mahrieh-coins" placeholder="مثلاً: ۱۱۴" min="0">
                                    <span class="suffix-text">سکه</span>
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label class="calc-label">مبلغ نقدی مهریه <small>(ریال، در صورت وجود)</small></label>
                                <div class="input-box">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <input type="number" id="mahrieh-cash" placeholder="مثلاً: ۵۰۰۰۰۰۰۰۰" min="0">
                                    <span class="suffix-text">ریال</span>
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label class="calc-label">سال عقد</label>
                                <div class="input-box">
                                    <i class="fas fa-calendar-alt"></i>
                                    <select class="premium-select" id="mahrieh-year">
                                        @for ($y = 1404; $y >= 1360; $y--)
                                            <option value="{{ $y }}" {{ $y == 1390 ? 'selected' : '' }}>سال
                                                {{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <button class="btn-calc" onclick="calcMahrieh()">
                                <i class="fas fa-calculator"></i> محاسبه ارزش روز مهریه
                            </button>
                        </div>

                        <div class="calc-result" id="result-mahrieh">
                            <div class="result-empty">
                                <i class="fas fa-ring"></i>
                                اطلاعات مهریه را در فرم وارد کرده<br>و روی دکمه محاسبه کلیک کنید
                            </div>
                            <div class="result-show" id="mahrieh-result-show">
                                <div class="result-tag">ارزش امروز مهریه (تخمینی)</div>
                                <div class="result-main-num" id="mahrieh-total">—</div>
                                <div class="result-unit">تومان</div>
                                <div class="result-items">
                                    <div class="result-item">
                                        <span class="ri-label">ارزش سکه‌ها</span>
                                        <span class="ri-val" id="mahrieh-coin-val">—</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">مبلغ نقدی (با تورم)</span>
                                        <span class="ri-val" id="mahrieh-cash-val">—</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">قیمت هر سکه امروز</span>
                                        <span class="ri-val">حدود ۷۰ میلیون تومان</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="calc-info">
                        <strong><i class="fas fa-exclamation-triangle"></i> توجه:</strong> این محاسبه
                        <strong>تخمینی</strong> بوده و بر اساس قیمت روز بازار سکه محاسبه می‌شود.
                        برای محاسبه دقیق وجه نقد بر اساس <strong>شاخص بانک مرکزی</strong> (که ملاک قطعی محاکم دادگستری است)،
                        حتماً با وکلای ما مشورت کنید.
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
         TAB 1: محاسبه دیه
    ══════════════════════════════════════════════════════════ --}}
        <div class="calc-panel" id="panel-1">
            <div class="calc-card">
                <div class="calc-card-header">
                    <div class="calc-card-header-icon"><i class="fas fa-user-injured"></i></div>
                    <div>
                        <h2>محاسبه دیه</h2>
                        <p>بر اساس مصوبه قوه قضاییه — سال ۱۴۰۴</p>
                    </div>
                </div>
                <div class="calc-body">
                    <div class="calc-inner">
                        <div class="calc-inputs">

                            <div class="form-group-c">
                                <label class="calc-label">نوع دیه</label>
                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <select class="premium-select" id="dieh-type" onchange="updateDiehInfo()">
                                        <option value="full_male">دیه کامل — مرد مسلمان</option>
                                        <option value="full_female">دیه کامل — زن مسلمان</option>
                                        <option value="custom">درصد خاص از دیه</option>
                                    </select>
                                </div>
                            </div>

                            <div id="dieh-percent-wrap" style="display:none;">
                                <div class="form-group-c">
                                    <label class="calc-label">درصد دیه</label>
                                    <div class="input-box">
                                        <i class="fas fa-percentage"></i>
                                        <input type="number" id="dieh-percent" placeholder="مثلاً: ۳۳" min="1"
                                            max="100">
                                        <span class="suffix-text">درصد</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label class="calc-label">ماه وقوع جنایت</label>
                                <div class="input-box">
                                    <i class="fas fa-moon"></i>
                                    <select class="premium-select" id="dieh-month">
                                        <option value="1">فروردین (ماه حرام - ثلث اضافه)</option>
                                        <option value="2">اردیبهشت</option>
                                        <option value="3">خرداد</option>
                                        <option value="4">تir</option>
                                        <option value="5">مرداد</option>
                                        <option value="6">شهریور</option>
                                        <option value="7">مهر (ماه حرام - ثلث اضافه)</option>
                                        <option value="8">آبان</option>
                                        <option value="9">آذر (ماه حرام - ثلث اضافه)</option>
                                        <option value="10">دی</option>
                                        <option value="11">بهمن</option>
                                        <option value="12">اسفند (ماه حرام - ثلث اضافه)</option>
                                    </select>
                                </div>
                            </div>

                            <button class="btn-calc" onclick="calcDieh()">
                                <i class="fas fa-calculator"></i> محاسبه دیه
                            </button>
                        </div>

                        <div class="calc-result" id="result-dieh">
                            <div class="result-empty">
                                <i class="fas fa-heartbeat"></i>
                                نوع دیه را مشخص کرده<br>و دکمه محاسبه را بزنید
                            </div>
                            <div class="result-show" id="dieh-result-show">
                                <div class="result-tag">مبلغ نهایی دیه</div>
                                <div class="result-main-num" id="dieh-total">—</div>
                                <div class="result-unit">تومان</div>
                                <div class="result-items">
                                    <div class="result-item">
                                        <span class="ri-label">دیه پایه سال ۱۴۰۴</span>
                                        <span class="ri-val">۱,۰۰۰,۰۰۰,۰۰۰ ت</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">ضریب ماه (عادی/حرام)</span>
                                        <span class="ri-val" id="dieh-month-coef">×۱</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">نوع محاسبه</span>
                                        <span class="ri-val" id="dieh-type-label">دیه کامل</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="calc-info">
                        <strong>مبنای محاسبه:</strong> دیه کامل مرد مسلمان در ماه‌های غیرحرام سال ۱۴۰۴ معادل یک میلیارد
                        تومان است. در صورت وقوع جنایت و فوت در یکی از ماه‌های حرام (محرم، رجب، ذی‌القعده، ذی‌الحجه)، یک سوم
                        به مبلغ دیه افزوده می‌شود (تغلیظ دیه).
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
         TAB 2: هزینه دادرسی
    ══════════════════════════════════════════════════════════ --}}
        <div class="calc-panel" id="panel-2">
            <div class="calc-card">
                <div class="calc-card-header">
                    <div class="calc-card-header-icon"><i class="fas fa-gavel"></i></div>
                    <div>
                        <h2>محاسبه هزینه دادرسی و تمبر مالیاتی</h2>
                        <p>بر اساس قانون وصول برخی از درآمدهای دولت</p>
                    </div>
                </div>
                <div class="calc-body">
                    <div class="calc-inner">
                        <div class="calc-inputs">

                            <div class="form-group-c">
                                <label class="calc-label">نوع دعوا</label>
                                <div class="input-box">
                                    <i class="fas fa-balance-scale"></i>
                                    <select class="premium-select" id="court-type">
                                        <option value="money">دعوای مالی</option>
                                        <option value="non-money">دعوای غیرمالی</option>
                                        <option value="appeal">تجدیدنظرخواهی</option>
                                        <option value="cassation">فرجام‌خواهی</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label class="calc-label">خواسته دعوا <small>(برای دعوای مالی)</small></label>
                                <div class="input-box">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <input type="number" id="court-amount" placeholder="مبلغ به تومان">
                                    <span class="suffix-text">تومان</span>
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label class="calc-label">مرحله رسیدگی</label>
                                <div class="input-box">
                                    <i class="fas fa-landmark"></i>
                                    <select class="premium-select" id="court-stage">
                                        <option value="first">بدوی (دادگاه اول)</option>
                                        <option value="appeal">تجدیدنظر</option>
                                        <option value="cassation">دیوان عالی کشور</option>
                                    </select>
                                </div>
                            </div>

                            <button class="btn-calc" onclick="calcCourt()">
                                <i class="fas fa-calculator"></i> برآورد هزینه دادرسی
                            </button>
                        </div>

                        <div class="calc-result" id="result-court">
                            <div class="result-empty">
                                <i class="fas fa-file-signature"></i>
                                مشخصات پرونده را وارد کرده<br>و محاسبه را بزنید
                            </div>
                            <div class="result-show" id="court-result-show">
                                <div class="result-tag">هزینه کل دادرسی (تقریبی)</div>
                                <div class="result-main-num" id="court-total">—</div>
                                <div class="result-unit">تومان</div>
                                <div class="result-items">
                                    <div class="result-item">
                                        <span class="ri-label">هزینه تمبر مالیاتی</span>
                                        <span class="ri-val" id="court-stamp">—</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">هزینه خدمات قضایی</span>
                                        <span class="ri-val">حدود ۵۰,۰۰۰ تومان</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">نرخ اعمال شده</span>
                                        <span class="ri-val" id="court-rate">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="calc-info">
                        <strong>نرخ مصوب هزینه دادرسی دعاوی مالی:</strong> تا ۲۰۰ میلیون تومان: ۳.۵٪ ارزش خواسته | از ۲۰۰
                        میلیون تا ۵۰۰ میلیون: ۳٪ | بیش از ۵۰۰ میلیون: ۲٪. هزینه‌های اعلام شده برای دعاوی غیرمالی ثابت و به
                        صورت میانگین در نظر گرفته شده است.
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
         TAB 3: خسارت تأخیر تأدیه
    ══════════════════════════════════════════════════════════ --}}
        <div class="calc-panel" id="panel-3">
            <div class="calc-card">
                <div class="calc-card-header">
                    <div class="calc-card-header-icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <h2>محاسبه خسارت تأخیر تأدیه</h2>
                        <p>بر اساس میانگین نرخ تورم اعلامی بانک مرکزی</p>
                    </div>
                </div>
                <div class="calc-body">
                    <div class="calc-inner">
                        <div class="calc-inputs">

                            <div class="form-group-c">
                                <label class="calc-label">مبلغ اصل بدهی</label>
                                <div class="input-box">
                                    <i class="fas fa-money-check-alt"></i>
                                    <input type="number" id="delay-amount" placeholder="مثلاً: ۵۰۰۰۰۰۰۰۰"
                                        min="0">
                                    <span class="suffix-text">تومان</span>
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label class="calc-label">سال سررسید بدهی <small>(سال شمسی)</small></label>
                                <div class="input-box">
                                    <i class="fas fa-calendar-times"></i>
                                    <input type="number" id="delay-from-year" placeholder="مثلاً: ۱۴۰۱" min="1380"
                                        max="1404">
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label class="calc-label">نرخ شاخص بانک مرکزی <small>(تخمینی)</small></label>
                                <div class="input-box">
                                    <i class="fas fa-percent"></i>
                                    <select class="premium-select" id="delay-rate">
                                        <option value="35">۳۵٪ (میانگین سال‌های اخیر)</option>
                                        <option value="40">۴۰٪</option>
                                        <option value="45">۴۵٪</option>
                                        <option value="50">۵۰٪</option>
                                        <option value="custom">بر اساس شاخص دقیق</option>
                                    </select>
                                </div>
                            </div>

                            <button class="btn-calc" onclick="calcDelay()">
                                <i class="fas fa-calculator"></i> محاسبه مبلغ با تأخیر
                            </button>
                        </div>

                        <div class="calc-result" id="result-delay">
                            <div class="result-empty">
                                <i class="fas fa-hourglass-half"></i>
                                مبلغ و سال سررسید را وارد کرده<br>و محاسبه را بزنید
                            </div>
                            <div class="result-show" id="delay-result-show">
                                <div class="result-tag">کل مبلغ قابل مطالبه</div>
                                <div class="result-main-num" id="delay-total">—</div>
                                <div class="result-unit">تومان</div>
                                <div class="result-items">
                                    <div class="result-item">
                                        <span class="ri-label">اصل مبلغ بدهی</span>
                                        <span class="ri-val" id="delay-principal">—</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">سود و خسارت تأخیر</span>
                                        <span class="ri-val" id="delay-penalty">—</span>
                                    </div>
                                    <div class="result-item">
                                        <span class="ri-label">مدت زمان تأخیر</span>
                                        <span class="ri-val" id="delay-duration">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="calc-info">
                        <strong>مبنای قانونی (ماده ۵۲۲ ق.آ.د.م):</strong> در دعاویی که موضوع آن دین و از نوع وجه رایج باشد،
                        در صورت مطالبه دائن و امتناع مدیون، دادگاه با رعایت تناسب تغییر شاخص سالانه که توسط بانک مرکزی تعیین
                        می‌گردد، خسارت را محاسبه و مورد حکم قرار می‌دهد.
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // ─── Tab Switching ─────────────────────────────────────────────────────
        function switchTab(idx) {
            document.querySelectorAll('.calc-tab').forEach((t, i) => t.classList.toggle('active', i === idx));
            document.querySelectorAll('.calc-panel').forEach((p, i) => p.classList.toggle('active', i === idx));
        }

        // ─── Helper: Format Number ────────────────────────────────────────────
        function fmt(n) {
            return Math.round(n).toLocaleString('fa-IR');
        }

        // ─── 1. محاسبه مهریه ─────────────────────────────────────────────────
        function calcMahrieh() {
            const coins = parseFloat(document.getElementById('mahrieh-coins').value) || 0;
            const cash = parseFloat(document.getElementById('mahrieh-cash').value) || 0;
            const year = parseInt(document.getElementById('mahrieh-year').value);

            const COIN_PRICE = 70_000_000; // تومان — تخمینی
            const BASE_INFLATION = 0.35;
            const currentYear = 1404;
            const years = Math.max(0, currentYear - year);

            const coinVal = coins * COIN_PRICE;
            const cashVal = cash / 10 * Math.pow(1 + BASE_INFLATION, years); // ریال→تومان با تورم
            const total = coinVal + cashVal;

            document.querySelector('#result-mahrieh .result-empty').style.display = 'none';
            const show = document.getElementById('mahrieh-result-show');
            show.style.display = 'block';
            document.getElementById('mahrieh-total').textContent = fmt(total) + ' ت';
            document.getElementById('mahrieh-coin-val').textContent = fmt(coinVal) + ' تومان';
            document.getElementById('mahrieh-cash-val').textContent = fmt(cashVal) + ' تومان';
        }

        // ─── 2. محاسبه دیه ───────────────────────────────────────────────────
        const SACRED_MONTHS = [1, 7, 11, 12]; // رجب=7، ذیقعده=9(هجری)... تقریبی شمسی
        function updateDiehInfo() {
            const t = document.getElementById('dieh-type').value;
            document.getElementById('dieh-percent-wrap').style.display = t === 'custom' ? 'block' : 'none';
        }

        function calcDieh() {
            const type = document.getElementById('dieh-type').value;
            const month = parseInt(document.getElementById('dieh-month').value);
            const percent = parseFloat(document.getElementById('dieh-percent').value) || 100;
            const BASE = 1_000_000_000; // ۱ میلیارد تومان ۱۴۰۴

            let base = BASE;
            if (type === 'full_female') base = BASE / 2;
            else if (type === 'custom') base = BASE * percent / 100;

            // ماه‌های حرام: فروردین ≈ رجب (تقریبی) - برای سادگی ماه ۱ و ۷ و ۹ و ۱۲
            const isSacred = [1, 7, 9, 12].includes(month);
            const coef = isSacred ? 4 / 3 : 1;
            const total = base * coef;

            document.querySelector('#result-dieh .result-empty').style.display = 'none';
            document.getElementById('dieh-result-show').style.display = 'block';
            document.getElementById('dieh-total').textContent = fmt(total) + ' ت';
            document.getElementById('dieh-month-coef').textContent = isSacred ? '×۱.۳۳ (ماه حرام)' : '×۱';
            document.getElementById('dieh-type-label').textContent = type === 'full_male' ? 'دیه کامل مرد' : type ===
                'full_female' ? 'دیه کامل زن' : percent + '٪ از دیه';
        }

        // ─── 3. هزینه دادرسی ────────────────────────────────────────────────
        function calcCourt() {
            const type = document.getElementById('court-type').value;
            const amount = parseFloat(document.getElementById('court-amount').value) || 0;
            const stage = document.getElementById('court-stage').value;

            let fee = 0,
                rate = '';
            if (type === 'non-money') {
                fee = stage === 'first' ? 200_000 : 150_000;
                rate = 'مقطوع';
            } else if (type === 'money') {
                if (amount <= 200_000_000) {
                    fee = amount * 0.035;
                    rate = '۳.۵٪';
                } else if (amount <= 500_000_000) {
                    fee = 200_000_000 * 0.035 + (amount - 200_000_000) * 0.03;
                    rate = '۳.۵٪ + ۳٪';
                } else {
                    fee = 200_000_000 * 0.035 + 300_000_000 * 0.03 + (amount - 500_000_000) * 0.02;
                    rate = '۲٪ (مازاد)';
                }
                if (stage === 'appeal') fee *= 0.5;
                if (stage === 'cassation') fee *= 0.3;
            } else if (type === 'appeal') {
                fee = 500_000;
                rate = 'مقطوع';
            } else if (type === 'cassation') {
                fee = 1_000_000;
                rate = 'مقطوع';
            }

            document.querySelector('#result-court .result-empty').style.display = 'none';
            document.getElementById('court-result-show').style.display = 'block';
            document.getElementById('court-total').textContent = fmt(fee + 50_000) + ' ت';
            document.getElementById('court-stamp').textContent = fmt(fee) + ' تومان';
            document.getElementById('court-rate').textContent = rate;
        }

        // ─── 4. خسارت تأخیر ─────────────────────────────────────────────────
        function calcDelay() {
            const principal = parseFloat(document.getElementById('delay-amount').value) || 0;
            const fromYear = parseInt(document.getElementById('delay-from-year').value) || 1400;
            const rateVal = document.getElementById('delay-rate').value;
            const rate = rateVal === 'custom' ? 40 : parseInt(rateVal);
            const years = Math.max(0, 1404 - fromYear);

            const penalty = principal * (Math.pow(1 + rate / 100, years) - 1);
            const total = principal + penalty;

            document.querySelector('#result-delay .result-empty').style.display = 'none';
            document.getElementById('delay-result-show').style.display = 'block';
            document.getElementById('delay-total').textContent = fmt(total) + ' ت';
            document.getElementById('delay-principal').textContent = fmt(principal) + ' تومان';
            document.getElementById('delay-penalty').textContent = fmt(penalty) + ' تومان';
            document.getElementById('delay-duration').textContent = years + ' سال';
        }
    </script>
@endpush
