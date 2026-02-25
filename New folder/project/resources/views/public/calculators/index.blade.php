@extends('layouts.public')
@section('title', 'محاسبات حقوقی | ابدالی و جوشقانی')

@push('styles')
<style>
    .calc-page { max-width: 1100px; margin: 0 auto; padding: 70px 20px; }

    /* ─── Tab Navigation ─────────────────────────────────────────── */
    .calc-tabs {
        display: flex; gap: 0; background: #fff;
        border-radius: 18px; padding: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
        margin-bottom: 40px; overflow-x: auto;
    }
    .calc-tab {
        flex: 1; padding: 16px 10px;
        border: none; background: none;
        font-family: 'Vazirmatn', sans-serif; font-size: 0.88rem; font-weight: 700;
        color: var(--text-body); cursor: pointer;
        border-radius: 12px; transition: 0.3s;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        min-width: 120px;
    }
    .calc-tab i { font-size: 1.3rem; }
    .calc-tab:hover { background: #fdfbf7; color: var(--navy); }
    .calc-tab.active {
        background: var(--navy); color: var(--gold-main);
        box-shadow: 0 5px 15px rgba(16,42,67,0.2);
    }
    .calc-tab.active i { color: var(--gold-main); }

    /* ─── Calculator Panels ─────────────────────────────────────── */
    .calc-panel { display: none; }
    .calc-panel.active { display: block; }

    .calc-card {
        background: #fff; border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.07);
        overflow: hidden; border-top: 5px solid var(--gold-main);
    }

    .calc-card-header {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        padding: 35px 45px; color: #fff;
        display: flex; align-items: center; gap: 20px;
    }
    .calc-card-header-icon {
        width: 65px; height: 65px;
        background: rgba(207,168,110,0.15); border: 2px solid rgba(207,168,110,0.3);
        border-radius: 18px; display: flex; align-items: center; justify-content: center;
        font-size: 1.7rem; color: var(--gold-main); flex-shrink: 0;
    }
    .calc-card-header h2 { font-size: 1.4rem; font-weight: 900; margin-bottom: 5px; }
    .calc-card-header p { color: rgba(255,255,255,0.65); font-size: 0.88rem; }

    .calc-body { padding: 45px; }

    /* Grid ورودی‌ها و نتیجه */
    .calc-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start; }

    .calc-inputs {}
    .calc-label {
        display: block; font-size: 0.88rem; font-weight: 700;
        color: var(--text-heading); margin-bottom: 8px;
    }
    .calc-label small { color: var(--text-body); font-weight: 500; }
    .calc-input-wrap { position: relative; margin-bottom: 22px; }
    .calc-input {
        width: 100%; padding: 14px 18px;
        border: 2px solid #e8e3db; border-radius: 12px;
        font-family: 'Vazirmatn', sans-serif; font-size: 1rem;
        background: #fdfbf7; transition: 0.3s; color: var(--text-heading);
    }
    .calc-input:focus { border-color: var(--gold-main); background: #fff; outline: none; box-shadow: 0 0 0 4px rgba(207,168,110,0.12); }
    .calc-input-suffix {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        font-size: 0.82rem; color: var(--text-body); font-weight: 600;
        pointer-events: none;
    }
    select.calc-input { appearance: none; cursor: pointer; }

    .btn-calc {
        width: 100%; padding: 16px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; border: none; border-radius: 12px;
        font-family: 'Vazirmatn', sans-serif; font-size: 1rem; font-weight: 700;
        cursor: pointer; transition: 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 0 8px 20px rgba(207,168,110,0.35); margin-top: 5px;
    }
    .btn-calc:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(207,168,110,0.5); }
    .btn-calc:active { transform: translateY(0); }

    /* Result Box */
    .calc-result {
        background: #fdfbf7; border-radius: 18px; padding: 30px;
        border: 2px solid #ede8de; min-height: 280px;
        display: flex; flex-direction: column; justify-content: center;
        position: relative; overflow: hidden;
    }
    .calc-result::before {
        content: '§'; position: absolute; left: -15px; bottom: -20px;
        font-size: 10rem; color: rgba(207,168,110,0.08);
        font-family: serif; pointer-events: none;
    }
    .result-empty {
        text-align: center; color: var(--text-body);
        opacity: 0.6; font-size: 0.88rem;
    }
    .result-empty i { font-size: 2.5rem; color: var(--gold-main); margin-bottom: 12px; display: block; opacity: 0.4; }

    .result-show { display: none; }
    .result-tag { font-size: 0.78rem; font-weight: 700; color: var(--gold-dark); margin-bottom: 8px; }
    .result-main-num {
        font-size: 2.2rem; font-weight: 900; color: var(--navy);
        line-height: 1.2; margin-bottom: 4px;
    }
    .result-unit { font-size: 0.9rem; color: var(--text-body); margin-bottom: 25px; }
    .result-items { display: flex; flex-direction: column; gap: 10px; }
    .result-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 14px; background: #fff; border-radius: 10px;
        border: 1px solid rgba(0,0,0,0.05); font-size: 0.85rem;
    }
    .result-item .ri-label { color: var(--text-body); }
    .result-item .ri-val { font-weight: 800; color: var(--text-heading); }

    /* Info box */
    .calc-info {
        margin-top: 30px; padding: 20px 25px;
        background: rgba(16,42,67,0.04); border-right: 4px solid var(--gold-main);
        border-radius: 0 12px 12px 0; font-size: 0.83rem; color: var(--text-body); line-height: 1.85;
    }
    .calc-info strong { color: var(--navy); }

    @media (max-width: 800px) {
        .calc-inner { grid-template-columns: 1fr; }
        .calc-body { padding: 30px 20px; }
        .calc-card-header { padding: 25px 20px; }
        .calc-tabs { gap: 0; }
        .calc-tab { font-size: 0.75rem; min-width: 80px; padding: 12px 5px; }
    }
</style>
@endpush

@section('content')

<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-calculator" style="color:var(--gold-main);margin-left:12px;"></i>محاسبات حقوقی</h1>
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
            <i class="fas fa-file-invoice"></i> هزینه دادرسی
        </button>
        <button class="calc-tab" onclick="switchTab(3)">
            <i class="fas fa-percentage"></i> خسارت تأخیر
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         TAB 0: محاسبه مهریه
    ══════════════════════════════════════════════════════════ --}}
    <div class="calc-panel active" id="panel-0">
        <div class="calc-card">
            <div class="calc-card-header">
                <div class="calc-card-header-icon"><i class="fas fa-ring"></i></div>
                <div>
                    <h2>محاسبه مهریه به نرخ روز</h2>
                    <p>بر اساس شاخص بهای کالاها و خدمات — بانک مرکزی ایران</p>
                </div>
            </div>
            <div class="calc-body">
                <div class="calc-inner">
                    <div class="calc-inputs">
                        <label class="calc-label">تعداد سکه طلا <small>(عدد)</small></label>
                        <div class="calc-input-wrap">
                            <input type="number" id="mahrieh-coins" class="calc-input" placeholder="مثلاً: ۱۱۴" min="0">
                            <span class="calc-input-suffix">سکه</span>
                        </div>

                        <label class="calc-label">مبلغ نقدی مهریه <small>(ریال، در صورت وجود)</small></label>
                        <div class="calc-input-wrap">
                            <input type="number" id="mahrieh-cash" class="calc-input" placeholder="مثلاً: ۵۰۰۰۰۰۰۰۰" min="0">
                            <span class="calc-input-suffix">ریال</span>
                        </div>

                        <label class="calc-label">سال عقد</label>
                        <div class="calc-input-wrap">
                            <select id="mahrieh-year" class="calc-input">
                                @for($y = 1404; $y >= 1360; $y--)
                                    <option value="{{ $y }}" {{ $y == 1390 ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <button class="btn-calc" onclick="calcMahrieh()">
                            <i class="fas fa-calculator"></i> محاسبه مهریه
                        </button>
                    </div>

                    <div class="calc-result" id="result-mahrieh">
                        <div class="result-empty">
                            <i class="fas fa-ring"></i>
                            اطلاعات مهریه را وارد کرده<br>و دکمه محاسبه را بزنید
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
                    <strong>⚠️ توجه:</strong> این محاسبه <strong>تخمینی</strong> بوده و بر اساس قیمت روز بازار سکه محاسبه می‌شود.
                    برای محاسبه دقیق بر اساس <strong>شاخص بانک مرکزی</strong> (که ملاک دادگاه است)، حتماً با وکیل مشورت کنید.
                    مبلغ نقدی بر اساس ضریب تورم سال‌های اخیر محاسبه شده است.
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
                <div class="calc-card-header-icon"><i class="fas fa-heartbeat"></i></div>
                <div>
                    <h2>محاسبه دیه</h2>
                    <p>بر اساس مصوبه قوه قضاییه — سال ۱۴۰۴</p>
                </div>
            </div>
            <div class="calc-body">
                <div class="calc-inner">
                    <div class="calc-inputs">
                        <label class="calc-label">نوع دیه</label>
                        <div class="calc-input-wrap">
                            <select id="dieh-type" class="calc-input" onchange="updateDiehInfo()">
                                <option value="full_male">دیه کامل — مرد مسلمان</option>
                                <option value="full_female">دیه کامل — زن مسلمان</option>
                                <option value="custom">درصد خاص از دیه</option>
                            </select>
                        </div>

                        <div id="dieh-percent-wrap" style="display:none;">
                            <label class="calc-label">درصد دیه</label>
                            <div class="calc-input-wrap">
                                <input type="number" id="dieh-percent" class="calc-input" placeholder="مثلاً: ۳۳" min="1" max="100">
                                <span class="calc-input-suffix">درصد</span>
                            </div>
                        </div>

                        <label class="calc-label">ماه وقوع جنایت</label>
                        <div class="calc-input-wrap">
                            <select id="dieh-month" class="calc-input">
                                <option value="1">فروردین (ثلث دیه اضافه)</option>
                                <option value="2">اردیبهشت</option>
                                <option value="3">خرداد</option>
                                <option value="4">تیر</option>
                                <option value="5">مرداد</option>
                                <option value="6">شهریور</option>
                                <option value="7">مهر</option>
                                <option value="8">آبان</option>
                                <option value="9">آذر</option>
                                <option value="10">دی</option>
                                <option value="11">بهمن</option>
                                <option value="12">اسفند</option>
                            </select>
                        </div>

                        <button class="btn-calc" onclick="calcDieh()">
                            <i class="fas fa-calculator"></i> محاسبه دیه
                        </button>
                    </div>

                    <div class="calc-result" id="result-dieh">
                        <div class="result-empty">
                            <i class="fas fa-heartbeat"></i>
                            نوع دیه را انتخاب کرده<br>و محاسبه را بزنید
                        </div>
                        <div class="result-show" id="dieh-result-show">
                            <div class="result-tag">مبلغ دیه (تومان)</div>
                            <div class="result-main-num" id="dieh-total">—</div>
                            <div class="result-unit">تومان</div>
                            <div class="result-items">
                                <div class="result-item">
                                    <span class="ri-label">دیه پایه سال ۱۴۰۴</span>
                                    <span class="ri-val">۱,۰۰۰,۰۰۰,۰۰۰ ت</span>
                                </div>
                                <div class="result-item">
                                    <span class="ri-label">ضریب ماه</span>
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
                    <strong>دیه کامل مرد مسلمان در سال ۱۴۰۴:</strong> یک میلیارد تومان مصوب قوه قضاییه.
                    دیه در ماه‌های حرام (رجب، ذی‌القعده، ذی‌الحجه، محرم) یک‌سوم اضافه می‌شود.
                    دیه زن نصف دیه مرد است.
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
                <div class="calc-card-header-icon"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <h2>محاسبه هزینه دادرسی و تمبر مالیاتی</h2>
                    <p>بر اساس قانون وصول برخی از درآمدهای دولت</p>
                </div>
            </div>
            <div class="calc-body">
                <div class="calc-inner">
                    <div class="calc-inputs">
                        <label class="calc-label">نوع دعوا</label>
                        <div class="calc-input-wrap">
                            <select id="court-type" class="calc-input">
                                <option value="money">دعوای مالی</option>
                                <option value="non-money">دعوای غیرمالی</option>
                                <option value="appeal">تجدیدنظرخواهی</option>
                                <option value="cassation">فرجام‌خواهی</option>
                            </select>
                        </div>

                        <label class="calc-label">خواسته دعوا <small>(برای دعوای مالی)</small></label>
                        <div class="calc-input-wrap">
                            <input type="number" id="court-amount" class="calc-input" placeholder="مبلغ به تومان">
                            <span class="calc-input-suffix">تومان</span>
                        </div>

                        <label class="calc-label">مرحله رسیدگی</label>
                        <div class="calc-input-wrap">
                            <select id="court-stage" class="calc-input">
                                <option value="first">بدوی (دادگاه اول)</option>
                                <option value="appeal">تجدیدنظر</option>
                                <option value="cassation">دیوان عالی</option>
                            </select>
                        </div>

                        <button class="btn-calc" onclick="calcCourt()">
                            <i class="fas fa-calculator"></i> محاسبه هزینه دادرسی
                        </button>
                    </div>

                    <div class="calc-result" id="result-court">
                        <div class="result-empty">
                            <i class="fas fa-file-invoice"></i>
                            اطلاعات دعوا را وارد کرده<br>و محاسبه را بزنید
                        </div>
                        <div class="result-show" id="court-result-show">
                            <div class="result-tag">هزینه دادرسی</div>
                            <div class="result-main-num" id="court-total">—</div>
                            <div class="result-unit">تومان</div>
                            <div class="result-items">
                                <div class="result-item">
                                    <span class="ri-label">هزینه تمبر مالیاتی</span>
                                    <span class="ri-val" id="court-stamp">—</span>
                                </div>
                                <div class="result-item">
                                    <span class="ri-label">هزینه پست قضایی (تقریبی)</span>
                                    <span class="ri-val">۵۰,۰۰۰ تومان</span>
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
                    <strong>نرخ هزینه دادرسی دعاوی مالی:</strong> تا ۲۰۰ میلیون تومان: ۳.۵٪ | از ۲۰۰ تا ۵۰۰ میلیون: ۳٪ | بیش از ۵۰۰ میلیون: ۲٪.
                    دعاوی غیرمالی هزینه ثابت دارند. این محاسبه تقریبی است.
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
                <div class="calc-card-header-icon"><i class="fas fa-percentage"></i></div>
                <div>
                    <h2>محاسبه خسارت تأخیر تأدیه</h2>
                    <p>بر اساس نرخ تورم اعلامی بانک مرکزی</p>
                </div>
            </div>
            <div class="calc-body">
                <div class="calc-inner">
                    <div class="calc-inputs">
                        <label class="calc-label">مبلغ اصل بدهی</label>
                        <div class="calc-input-wrap">
                            <input type="number" id="delay-amount" class="calc-input" placeholder="مثلاً: ۵۰۰۰۰۰۰۰۰" min="0">
                            <span class="calc-input-suffix">تومان</span>
                        </div>

                        <label class="calc-label">تاریخ سررسید (سال شمسی)</label>
                        <div class="calc-input-wrap">
                            <input type="number" id="delay-from-year" class="calc-input" placeholder="مثلاً: ۱۴۰۱" min="1380" max="1404">
                        </div>

                        <label class="calc-label">نرخ شاخص بانک مرکزی (تخمینی)</label>
                        <div class="calc-input-wrap">
                            <select id="delay-rate" class="calc-input">
                                <option value="35">۳۵٪ (میانگین سال‌های اخیر)</option>
                                <option value="40">۴۰٪</option>
                                <option value="45">۴۵٪</option>
                                <option value="50">۵۰٪</option>
                                <option value="custom">نرخ دقیق بانک مرکزی</option>
                            </select>
                        </div>

                        <button class="btn-calc" onclick="calcDelay()">
                            <i class="fas fa-calculator"></i> محاسبه خسارت
                        </button>
                    </div>

                    <div class="calc-result" id="result-delay">
                        <div class="result-empty">
                            <i class="fas fa-percentage"></i>
                            اطلاعات را وارد کرده<br>و محاسبه را بزنید
                        </div>
                        <div class="result-show" id="delay-result-show">
                            <div class="result-tag">کل مبلغ قابل مطالبه</div>
                            <div class="result-main-num" id="delay-total">—</div>
                            <div class="result-unit">تومان</div>
                            <div class="result-items">
                                <div class="result-item">
                                    <span class="ri-label">اصل بدهی</span>
                                    <span class="ri-val" id="delay-principal">—</span>
                                </div>
                                <div class="result-item">
                                    <span class="ri-label">خسارت تأخیر</span>
                                    <span class="ri-val" id="delay-penalty">—</span>
                                </div>
                                <div class="result-item">
                                    <span class="ri-label">مدت تأخیر</span>
                                    <span class="ri-val" id="delay-duration">—</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="calc-info">
                    <strong>مبنای قانونی:</strong> مطابق ماده ۵۲۲ قانون آیین دادرسی مدنی، خسارت تأخیر تأدیه بر اساس
                    <strong>نرخ تورم اعلامی بانک مرکزی</strong> محاسبه می‌شود. برای دریافت نرخ دقیق، با دفتر ما تماس بگیرید.
                </div>
            </div>
        </div>
    </div>

    {{-- ─── لینک مشاوره --}}
    <div style="text-align:center;margin-top:50px;padding:40px;background:#fff;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.06);">
        <i class="fas fa-user-tie" style="font-size:2.5rem;color:var(--gold-main);margin-bottom:15px;display:block;"></i>
        <h3 style="font-size:1.3rem;color:var(--text-heading);margin-bottom:10px;">نیاز به محاسبه دقیق‌تر دارید؟</h3>
        <p style="color:var(--text-body);margin-bottom:25px;">این ابزارها تخمینی هستند. برای محاسبه دقیق حقوقی و پیگیری پرونده، با وکلای ما مشورت کنید.</p>
        <a href="{{ route('reserve.index') }}" style="display:inline-flex;align-items:center;gap:10px;background:linear-gradient(135deg,var(--navy),#1e3a5f);color:#fff;padding:14px 30px;border-radius:12px;font-weight:700;text-decoration:none;box-shadow:0 8px 20px rgba(16,42,67,0.2);">
            <i class="fas fa-calendar-check"></i> رزرو مشاوره رایگان
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ─── Tab Switching ─────────────────────────────────────────────────────
function switchTab(idx) {
    document.querySelectorAll('.calc-tab').forEach((t,i) => t.classList.toggle('active', i === idx));
    document.querySelectorAll('.calc-panel').forEach((p,i) => p.classList.toggle('active', i === idx));
}

// ─── Helper: Format Number ────────────────────────────────────────────
function fmt(n) {
    return Math.round(n).toLocaleString('fa-IR');
}

// ─── 1. محاسبه مهریه ─────────────────────────────────────────────────
function calcMahrieh() {
    const coins   = parseFloat(document.getElementById('mahrieh-coins').value) || 0;
    const cash    = parseFloat(document.getElementById('mahrieh-cash').value) || 0;
    const year    = parseInt(document.getElementById('mahrieh-year').value);

    const COIN_PRICE = 70_000_000; // تومان — تخمینی
    const BASE_INFLATION = 0.35;
    const currentYear = 1404;
    const years = Math.max(0, currentYear - year);

    const coinVal  = coins * COIN_PRICE;
    const cashVal  = cash / 10 * Math.pow(1 + BASE_INFLATION, years); // ریال→تومان با تورم
    const total    = coinVal + cashVal;

    document.querySelector('#result-mahrieh .result-empty').style.display = 'none';
    const show = document.getElementById('mahrieh-result-show');
    show.style.display = 'block';
    document.getElementById('mahrieh-total').textContent    = fmt(total) + ' ت';
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
    const type    = document.getElementById('dieh-type').value;
    const month   = parseInt(document.getElementById('dieh-month').value);
    const percent = parseFloat(document.getElementById('dieh-percent').value) || 100;
    const BASE    = 1_000_000_000; // ۱ میلیارد تومان ۱۴۰۴

    let base = BASE;
    if (type === 'full_female') base = BASE / 2;
    else if (type === 'custom')  base = BASE * percent / 100;

    // ماه‌های حرام: فروردین ≈ رجب (تقریبی) - برای سادگی ماه ۱ و ۷ و ۹ و ۱۲
    const isSacred = [1, 7, 9, 12].includes(month);
    const coef     = isSacred ? 4/3 : 1;
    const total    = base * coef;

    document.querySelector('#result-dieh .result-empty').style.display = 'none';
    document.getElementById('dieh-result-show').style.display = 'block';
    document.getElementById('dieh-total').textContent        = fmt(total) + ' ت';
    document.getElementById('dieh-month-coef').textContent   = isSacred ? '×۱.۳۳ (ماه حرام)' : '×۱';
    document.getElementById('dieh-type-label').textContent   = type === 'full_male' ? 'دیه کامل مرد' : type === 'full_female' ? 'دیه کامل زن' : percent + '٪ از دیه';
}

// ─── 3. هزینه دادرسی ────────────────────────────────────────────────
function calcCourt() {
    const type   = document.getElementById('court-type').value;
    const amount = parseFloat(document.getElementById('court-amount').value) || 0;
    const stage  = document.getElementById('court-stage').value;

    let fee = 0, rate = '';
    if (type === 'non-money') {
        fee  = stage === 'first' ? 200_000 : 150_000;
        rate = 'مقطوع';
    } else if (type === 'money') {
        if (amount <= 200_000_000)      { fee = amount * 0.035; rate = '۳.۵٪'; }
        else if (amount <= 500_000_000) { fee = 200_000_000 * 0.035 + (amount - 200_000_000) * 0.03; rate = '۳.۵٪ + ۳٪'; }
        else                             { fee = 200_000_000 * 0.035 + 300_000_000 * 0.03 + (amount - 500_000_000) * 0.02; rate = '۲٪ (مازاد)'; }
        if (stage === 'appeal')    fee *= 0.5;
        if (stage === 'cassation') fee *= 0.3;
    } else if (type === 'appeal')    { fee = 500_000; rate = 'مقطوع'; }
    else if (type === 'cassation')   { fee = 1_000_000; rate = 'مقطوع'; }

    document.querySelector('#result-court .result-empty').style.display = 'none';
    document.getElementById('court-result-show').style.display = 'block';
    document.getElementById('court-total').textContent = fmt(fee + 50_000) + ' ت';
    document.getElementById('court-stamp').textContent = fmt(fee) + ' تومان';
    document.getElementById('court-rate').textContent  = rate;
}

// ─── 4. خسارت تأخیر ─────────────────────────────────────────────────
function calcDelay() {
    const principal = parseFloat(document.getElementById('delay-amount').value) || 0;
    const fromYear  = parseInt(document.getElementById('delay-from-year').value) || 1400;
    const rateVal   = document.getElementById('delay-rate').value;
    const rate      = rateVal === 'custom' ? 40 : parseInt(rateVal);
    const years     = Math.max(0, 1404 - fromYear);

    const penalty   = principal * (Math.pow(1 + rate/100, years) - 1);
    const total     = principal + penalty;

    document.querySelector('#result-delay .result-empty').style.display = 'none';
    document.getElementById('delay-result-show').style.display = 'block';
    document.getElementById('delay-total').textContent     = fmt(total) + ' ت';
    document.getElementById('delay-principal').textContent  = fmt(principal) + ' تومان';
    document.getElementById('delay-penalty').textContent    = fmt(penalty) + ' تومان';
    document.getElementById('delay-duration').textContent   = years + ' سال';
}
</script>
@endpush
