@extends('layouts.public')
@section('title', 'تماس با ما | ابدالی و جوشقانی')

@push('styles')
    <style>
        :root {
            --gold-main: #d4af37;
            --gold-dark: #aa8222;
            --gold-light: #f9f1d8;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --text-heading: #0f172a;
            --text-body: #64748b;
            --shadow-soft: 0 20px 40px -15px rgba(212, 175, 55, 0.15);
            --shadow-card: 0 20px 40px -5px rgba(15, 23, 42, 0.05);
            --radius-md: 16px;
            --radius-lg: 30px;
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .contact-page {
            max-width: 1250px;
            margin: 0 auto;
            padding: 80px 20px 120px;
        }

        /* ─── Grid اصلی ─────────────────────────────────────────────── */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 50px;
            margin-bottom: 80px;
            align-items: start;
        }

        /* ─── ستون چپ: اطلاعات ──────────────────────────────────────── */
        .info-col {
            display: flex;
            flex-direction: column;
            gap: 25px;
            position: sticky;
            top: 120px;
        }

        .info-box {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 35px 30px;
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: flex-start;
            gap: 20px;
            transition: var(--transition);
        }

        .info-box:hover {
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateY(-5px);
            box-shadow: var(--shadow-soft);
        }

        .info-box-icon {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--gold-dark);
            transition: var(--transition);
        }

        .info-box:hover .info-box-icon {
            background: var(--gold-main);
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }

        .info-box-text h4 {
            font-size: 1.1rem;
            font-weight: 900;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .info-box-text p {
            font-size: 0.95rem;
            color: var(--text-body);
            line-height: 1.8;
        }

        .info-box-text a {
            color: var(--navy);
            font-weight: 800;
            transition: var(--transition);
        }

        .info-box-text a:hover {
            color: var(--gold-main);
        }

        /* کارت ساعت کاری */
        .hours-card {
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            border-radius: var(--radius-md);
            padding: 40px 35px;
            color: #fff;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            position: relative;
            overflow: hidden;
        }

        .hours-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--gold-main), var(--gold-light));
        }

        .hours-card h4 {
            color: var(--gold-main);
            font-size: 1.2rem;
            font-weight: 900;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hour-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
            font-size: 0.95rem;
        }

        .hour-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .hour-row span:first-child {
            color: #cbd5e1;
        }

        .hour-row span:last-child {
            font-weight: 800;
            color: #fff;
        }

        .badge-open {
            background: rgba(37, 211, 102, 0.15);
            color: #4ade80;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid rgba(37, 211, 102, 0.3);
        }

        .badge-by-appointment {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Social Links */
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: var(--transition);
            text-decoration: none;
            border: 1px solid transparent;
        }

        .social-btn.instagram {
            background: rgba(225, 48, 108, 0.08);
            color: #e1306c;
            border-color: rgba(225, 48, 108, 0.2);
        }

        .social-btn.telegram {
            background: rgba(0, 136, 204, 0.08);
            color: #0088cc;
            border-color: rgba(0, 136, 204, 0.2);
        }

        .social-btn.whatsapp {
            background: rgba(37, 211, 102, 0.08);
            color: #25d366;
            border-color: rgba(37, 211, 102, 0.2);
        }

        .social-btn:hover {
            transform: translateY(-5px);
            background: currentColor;
            color: #fff;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* ─── ستون راست: فرم‌ها ────────────────────────────────────────── */
        .forms-col {
            display: flex;
            flex-direction: column;
            gap: 35px;
        }

        /* باکس رزرو وکیل (جدید) */
        .reserve-box {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: var(--radius-lg);
            padding: 45px 40px;
            border: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        .reserve-box::after {
            content: '\f073';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: -20px;
            bottom: -30px;
            font-size: 12rem;
            color: rgba(212, 175, 55, 0.04);
            pointer-events: none;
            line-height: 1;
        }

        .reserve-box h2 {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .reserve-box h2 i {
            color: var(--gold-main);
        }

        .reserve-box p {
            color: var(--text-body);
            font-size: 1rem;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .reserve-form {
            display: flex;
            gap: 20px;
            position: relative;
            z-index: 2;
            align-items: center;
        }

        .reserve-form .input-box {
            flex: 1;
            background: #fff;
            margin: 0;
        }

        .reserve-form select {
            flex: 1;
            border: none !important;
            background: transparent !important;
            padding: 16px 0;
            font-family: inherit;
            font-size: 1rem;
            color: var(--text-heading);
            outline: none !important;
            box-shadow: none !important;
            appearance: none;
            cursor: pointer;
        }

        .btn-reserve {
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: #fff;
            padding: 17px 35px;
            border-radius: 14px;
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            box-shadow: 0 10px 25px -5px rgba(212, 175, 55, 0.5);
        }

        .btn-reserve:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px -5px rgba(212, 175, 55, 0.7);
        }

        /* باکس تماس با ما */
        .contact-form-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 50px;
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .contact-form-card h2 {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--text-heading);
            margin-bottom: 10px;
        }

        .contact-form-card p {
            color: var(--text-body);
            margin-bottom: 40px;
            font-size: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-label-p {
            display: block;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .form-label-p span {
            color: #e74c3c;
            margin-right: 4px;
        }

        /* فیکس برای select در فرم تماس */
        .contact-form-card select {
            flex: 1;
            border: none !important;
            background: transparent !important;
            padding: 15px 0;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-heading);
            outline: none !important;
            box-shadow: none !important;
            appearance: none;
            cursor: pointer;
        }

        .btn-submit-p {
            width: 100%;
            padding: 18px;
            margin-top: 15px;
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            color: #fff;
            border: none;
            border-radius: 16px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
        }

        .btn-submit-p:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35);
            background: linear-gradient(135deg, var(--navy-light), var(--navy));
        }

        /* ─── نقشه ──────────────────────────────────────────────────── */
        .map-section {
            background: #fff;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .map-section iframe {
            width: 100%;
            height: 450px;
            border: none;
            display: block;
            filter: contrast(1.1) saturate(0.9);
        }

        .map-overlay {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 25px 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-right: 5px solid var(--gold-main);
            max-width: 320px;
        }

        .map-overlay h4 {
            font-size: 1.15rem;
            font-weight: 900;
            color: var(--navy);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-overlay p {
            font-size: 0.95rem;
            color: var(--text-body);
            line-height: 1.8;
        }

        /* ─── Quick Contact Lawyers ──────────────────────────────────── */
        .quick-lawyers {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 50px;
        }

        .ql-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 30px;
            box-shadow: var(--shadow-card);
            display: flex;
            align-items: center;
            gap: 25px;
            border: 1px solid rgba(0, 0, 0, 0.03);
            transition: var(--transition);
        }

        .ql-card:hover {
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateY(-5px);
            box-shadow: var(--shadow-soft);
        }

        .ql-avatar {
            width: 75px;
            height: 75px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--gold-main);
            flex-shrink: 0;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
        }

        .ql-info h4 {
            font-size: 1.1rem;
            font-weight: 900;
            color: var(--text-heading);
            margin-bottom: 6px;
        }

        .ql-info p {
            font-size: 0.9rem;
            color: var(--text-body);
            margin-bottom: 15px;
        }

        .ql-actions {
            display: flex;
            gap: 10px;
        }

        .ql-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 10px 18px;
            border-radius: 10px;
            transition: var(--transition);
            text-decoration: none;
        }

        .ql-btn.call {
            background: #f1f5f9;
            color: var(--navy);
        }

        .ql-btn.call:hover {
            background: var(--navy);
            color: #fff;
        }

        .ql-btn.whatsapp {
            background: rgba(37, 211, 102, 0.1);
            color: #25d366;
        }

        .ql-btn.whatsapp:hover {
            background: #25d366;
            color: #fff;
        }

        @media (max-width: 960px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .info-col {
                position: static;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .reserve-form {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-reserve {
                justify-content: center;
                padding: 18px;
            }

            .quick-lawyers {
                grid-template-columns: 1fr;
            }

            .contact-form-card {
                padding: 40px 25px;
            }

            .map-overlay {
                position: static;
                max-width: 100%;
                border-radius: 0;
                border-right: none;
                border-bottom: 5px solid var(--gold-main);
            }
        }
    </style>
@endpush

@section('content')

    <div class="page-banner" style="margin-right: 3%; margin-top: 3%; border-radius: 20px;">
        <div class="page-banner-inner">
            <h1><i class="fas fa-phone-alt" style="color:var(--gold-main);margin-left:15px;"></i>ارتباط با دفتر وکالت</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">صفحه اصلی</a>
                <i class="fas fa-chevron-left"></i>
                <span>تماس با ما</span>
            </div>
        </div>
    </div>

    <div class="contact-page">

        <div class="contact-grid">

            {{-- ─── ستون چپ: اطلاعات ─────────────────────────────────── --}}
            <div class="info-col">

                <div class="info-box">
                    <div class="info-box-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-box-text">
                        <h4>آدرس دفتر اصفهان</h4>
                        <p>میدان جمهوری، جنب کلانتری ۱۲،<br>ساختمان جمهوری، طبقه اول، واحد ۴</p>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-box-icon"><i class="fas fa-phone-volume"></i></div>
                    <div class="info-box-text">
                        <h4>مشاوره تلفنی مستقیم</h4>
                        <p>
                            <a href="tel:09131146888">۰۹۱۳۱۱۴۶۸۸۸</a> — دکتر بابک ابدالی<br>
                            <a href="tel:09132888859">۰۹۱۳۲۸۸۸۸۵۹</a> — سرکار خانم جوشقانی
                        </p>
                    </div>
                </div>

                <div class="hours-card">
                    <h4><i class="far fa-clock"></i> ساعات پذیرش حضوری</h4>
                    <div class="hour-row">
                        <span>شنبه تا چهارشنبه</span>
                        <span>۱۷:۰۰ – ۲۱:۰۰ <span class="badge-open">باز است</span></span>
                    </div>
                    <div class="hour-row">
                        <span>پنج‌شنبه‌ها</span>
                        <span><span class="badge-by-appointment">فقط با وقت قبلی</span></span>
                    </div>
                    <div class="hour-row">
                        <span>ایام تعطیل رسمی</span>
                        <span style="color:rgba(255,255,255,0.4);">تعطیل</span>
                    </div>
                </div>

                <div>
                    <p style="font-size:0.9rem;color:var(--text-body);font-weight:700;margin-bottom:15px;">ارتباط سریع در
                        شبکه‌های اجتماعی:</p>
                    <div class="social-links">
                        <a href="#" class="social-btn instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn telegram"><i class="fab fa-telegram-plane"></i></a>
                        <a href="https://wa.me/989131146888" target="_blank" class="social-btn whatsapp"><i
                                class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

            </div>

            {{-- ─── ستون راست: فرم‌ها ──────────────────────────────────────── --}}
            <div class="forms-col">

                {{-- باکس جدید رزرو وکیل --}}
                <div class="reserve-box">
                    <h2><i class="fas fa-calendar-check"></i> رزرو نوبت مشاوره</h2>
                    <p>برای دریافت وقت مشاوره حضوری یا آنلاین، وکیل مورد نظر خود را انتخاب کرده و به صفحه رزرو هدایت شوید.
                    </p>

                    {{-- فرم با متد GET به صفحه رزرو --}}
                    <form action="{{ route('reserve.index') }}" method="GET" class="reserve-form">
                        <div class="input-box">
                            <i class="fas fa-user-tie"></i>
                            <select class="premium-select" name="lawyer" required>
                                <option value="" disabled selected>انتخاب وکیل متخصص...</option>
                                <option value="babak-abdali">دکتر بابک ابدالی (دعاوی ملکی و تجاری)</option>
                                <option value="zahra-joshaghani">سرکار خانم زهرا جوشقانی (دعاوی خانواده)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-reserve">
                            شروع فرآیند رزرو <i class="fas fa-arrow-left"></i>
                        </button>
                    </form>
                </div>

                {{-- فرم تماس با ما --}}
                <div class="contact-form-card">
                    <h2>ارسال پیام و طرح سوال</h2>
                    <p>اگر سوال کوتاهی دارید یا نیاز به راهنمایی اولیه دارید، فرم زیر را تکمیل کنید تا کارشناسان ما پاسخگوی
                        شما باشند.</p>

                    @if (session('success'))
                        <div
                            style="background:rgba(37,211,102,0.1);border:1px solid rgba(37,211,102,0.3);border-radius:12px;padding:15px 20px;margin-bottom:25px;color:#16a34a;font-weight:800; display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-check-circle" style="font-size:1.2rem;"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf

                        <div class="form-row">
                            <div class="form-group-c">
                                <label for="name" class="form-label-p">نام و نام خانوادگی <span>*</span></label>
                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="name" name="name" placeholder="مثال: علی محمدی"
                                        required value="{{ old('name') }}">
                                </div>
                                @error('name')
                                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group-c">
                                <label for="phone" class="form-label-p">شماره موبایل <span>*</span></label>
                                <div class="input-box">
                                    <i class="fas fa-mobile-alt"></i>
                                    <input type="tel" id="phone" name="phone" placeholder="۰۹۱۲۳۴۵۶۷۸۹" required
                                        style="direction:ltr; text-align:right;" value="{{ old('phone') }}">
                                </div>
                                @error('phone')
                                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group-c">
                                <label for="service" class="form-label-p">حوزه درخواست حقوقی</label>
                                <div class="input-box">
                                    <i class="fas fa-gavel"></i>
                                    <select class="premium-select" id="service" name="service">
                                        <option value="" disabled selected>موضوع پرونده...</option>
                                        <option value="family" {{ old('service') == 'family' ? 'selected' : '' }}>حقوق
                                            خانواده و طلاق</option>
                                        <option value="commercial" {{ old('service') == 'commercial' ? 'selected' : '' }}>
                                            دعاوی تجاری و شرکت‌ها</option>
                                        <option value="real-estate"
                                            {{ old('service') == 'real-estate' ? 'selected' : '' }}>دعاوی ملکی و اراضی
                                        </option>
                                        <option value="criminal" {{ old('service') == 'criminal' ? 'selected' : '' }}>
                                            دعاوی کیفری و جرائم</option>
                                        <option value="other" {{ old('service') == 'other' ? 'selected' : '' }}>سایر
                                            موارد</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group-c">
                                <label for="email" class="form-label-p">پست الکترونیک <small
                                        style="color:#94a3b8;font-weight:600;">(اختیاری)</small></label>
                                <div class="input-box">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="email" name="email" placeholder="example@email.com"
                                        style="direction:ltr; text-align:right;" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group-c">
                            <label for="message" class="form-label-p">شرح مختصر موضوع <span>*</span></label>
                            <div class="input-box textarea-box">
                                <i class="fas fa-comment-dots"></i>
                                <textarea id="message" name="message" required
                                    placeholder="لطفاً خلاصه‌ای از مشکل حقوقی یا سوال خود را به صورت واضح بیان کنید...">{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit-p">
                            ارسال پیام به دفتر <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- ─── نقشه ──────────────────────────────────────────────── --}}
        <div class="map-section">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3355.0!2d51.6746!3d32.6573!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzLCsDM5JzI2LjMiTiA1McKwNDAnMjguNiJF!5e0!3m2!1sfa!2sir!4v1234567890"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <div class="map-overlay">
                <h4><i class="fas fa-map-marker-alt" style="color:var(--gold-main);"></i> موقعیت دفتر در نقشه</h4>
                <p>اصفهان، میدان جمهوری، ساختمان جمهوری، طبقه اول، واحد ۴</p>
            </div>
        </div>

        {{-- ─── Quick Contact per Lawyer ──────────────────────────── --}}
        <div class="quick-lawyers">
            <div class="ql-card">
                <div class="ql-avatar">ب</div>
                <div class="ql-info">
                    <h4>دکتر بابک ابدالی</h4>
                    <p>متخصص دعاوی تجاری، ملکی و کیفری</p>
                    <div class="ql-actions">
                        <a href="tel:09131146888" class="ql-btn call">
                            <i class="fas fa-phone"></i> ۰۹۱۳۱۱۴۶۸۸۸
                        </a>
                        <a href="https://wa.me/989131146888" target="_blank" class="ql-btn whatsapp">
                            <i class="fab fa-whatsapp"></i> پیام
                        </a>
                    </div>
                </div>
            </div>
            <div class="ql-card">
                <div class="ql-avatar" style="background:linear-gradient(135deg,#c5a059,#9e7f41);">ز</div>
                <div class="ql-info">
                    <h4>سرکار خانم زهرا جوشقانی</h4>
                    <p>متخصص حقوق خانواده، ارث و احوال شخصیه</p>
                    <div class="ql-actions">
                        <a href="tel:09132888859" class="ql-btn call">
                            <i class="fas fa-phone"></i> ۰۹۱۳۲۸۸۸۸۵۹
                        </a>
                        <a href="https://wa.me/989132888859" target="_blank" class="ql-btn whatsapp">
                            <i class="fab fa-whatsapp"></i> پیام
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
