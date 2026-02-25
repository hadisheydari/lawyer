@extends('layouts.public')
@section('title', 'تماس با ما | ابدالی و جوشقانی')

@push('styles')
<style>
    .contact-page { max-width: 1200px; margin: 0 auto; padding: 80px 20px; }

    /* ─── Grid اصلی ─────────────────────────────────────────────── */
    .contact-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 40px; margin-bottom: 60px; }

    /* ─── ستون چپ: اطلاعات ──────────────────────────────────────── */
    .info-col { display: flex; flex-direction: column; gap: 20px; }

    .info-box {
        background: #fff; border-radius: 20px; padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        display: flex; align-items: flex-start; gap: 18px;
        transition: 0.3s;
    }
    .info-box:hover { border-color: var(--gold-main); transform: translateX(-4px); }

    .info-box-icon {
        width: 52px; height: 52px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        border-radius: 14px; display: flex; align-items: center;
        justify-content: center; font-size: 1.2rem;
        color: var(--gold-main);
        box-shadow: 0 5px 15px rgba(16,42,67,0.2);
    }
    .info-box-text h4 { font-size: 1rem; font-weight: 800; color: var(--text-heading); margin-bottom: 6px; }
    .info-box-text p { font-size: 0.88rem; color: var(--text-body); line-height: 1.8; }
    .info-box-text a { color: var(--text-body); transition: 0.3s; }
    .info-box-text a:hover { color: var(--gold-main); }

    /* کارت ساعت کاری */
    .hours-card {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        border-radius: 20px; padding: 30px;
        color: #fff;
    }
    .hours-card h4 { color: var(--gold-main); font-size: 1rem; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; }
    .hour-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.07); font-size: 0.88rem; }
    .hour-row:last-child { border-bottom: none; }
    .hour-row span:first-child { color: rgba(255,255,255,0.7); }
    .hour-row span:last-child { font-weight: 700; color: #fff; }
    .badge-open { background: rgba(16,185,129,0.2); color: #10b981; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .badge-by-appointment { background: rgba(245,158,11,0.2); color: #f59e0b; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }

    /* Social Links */
    .social-links { display: flex; gap: 12px; }
    .social-btn {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; transition: 0.3s; text-decoration: none;
    }
    .social-btn.instagram { background: rgba(225,48,108,0.1); color: #e1306c; }
    .social-btn.telegram { background: rgba(0,136,204,0.1); color: #0088cc; }
    .social-btn.whatsapp { background: rgba(37,211,102,0.1); color: #25d366; }
    .social-btn:hover { transform: translateY(-3px) scale(1.1); }

    /* ─── ستون راست: فرم ────────────────────────────────────────── */
    .contact-form-card {
        background: #fff; border-radius: 24px; padding: 50px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.07);
        border-top: 5px solid var(--gold-main);
    }
    .contact-form-card h2 { font-size: 1.6rem; font-weight: 900; color: var(--text-heading); margin-bottom: 8px; }
    .contact-form-card p { color: var(--text-body); margin-bottom: 35px; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group-p { margin-bottom: 20px; }
    .form-label-p {
        display: block; font-size: 0.88rem; font-weight: 700;
        color: var(--text-heading); margin-bottom: 8px;
    }
    .form-label-p span { color: #e74c3c; margin-right: 3px; }
    .form-input-p {
        width: 100%; padding: 13px 16px;
        border: 2px solid #e8e3db; border-radius: 12px;
        font-family: 'Vazirmatn', sans-serif; font-size: 0.92rem;
        background: #fdfbf7; transition: 0.3s; color: var(--text-heading);
    }
    .form-input-p:focus {
        border-color: var(--gold-main); background: #fff;
        outline: none; box-shadow: 0 0 0 4px rgba(207,168,110,0.12);
    }
    select.form-input-p { appearance: none; cursor: pointer; }
    textarea.form-input-p { resize: vertical; min-height: 130px; }

    .btn-submit-p {
        width: 100%; padding: 16px;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        color: #fff; border: none; border-radius: 12px;
        font-family: 'Vazirmatn', sans-serif; font-size: 1rem; font-weight: 700;
        cursor: pointer; transition: 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 0 8px 20px rgba(16,42,67,0.2);
    }
    .btn-submit-p:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(16,42,67,0.3);
    }

    /* ─── نقشه ──────────────────────────────────────────────────── */
    .map-section {
        background: #fff; border-radius: 24px; overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.07); position: relative;
    }
    .map-section iframe {
        width: 100%; height: 380px; border: none; display: block;
        filter: grayscale(20%);
    }
    .map-overlay {
        position: absolute; top: 20px; right: 20px;
        background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
        border-radius: 14px; padding: 18px 22px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border-right: 4px solid var(--gold-main);
        max-width: 250px;
    }
    .map-overlay h4 { font-size: 0.95rem; font-weight: 800; color: var(--text-heading); margin-bottom: 6px; }
    .map-overlay p { font-size: 0.8rem; color: var(--text-body); line-height: 1.7; }

    /* ─── Quick Contact Lawyers ──────────────────────────────────── */
    .quick-lawyers { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 40px; }
    .ql-card {
        background: #fff; border-radius: 18px; padding: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        display: flex; align-items: center; gap: 18px;
        border: 1px solid rgba(0,0,0,0.04); transition: 0.3s;
    }
    .ql-card:hover { border-color: var(--gold-main); transform: translateY(-3px); }
    .ql-avatar {
        width: 60px; height: 60px; border-radius: 50%;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; font-weight: 900; color: var(--gold-main);
        flex-shrink: 0; border: 3px solid rgba(207,168,110,0.3);
    }
    .ql-info h4 { font-size: 0.95rem; font-weight: 800; color: var(--text-heading); margin-bottom: 4px; }
    .ql-info p { font-size: 0.78rem; color: var(--text-body); margin-bottom: 10px; }
    .ql-actions { display: flex; gap: 8px; }
    .ql-btn {
        display: flex; align-items: center; gap: 5px;
        font-size: 0.75rem; font-weight: 700;
        padding: 6px 12px; border-radius: 8px;
        transition: 0.3s; text-decoration: none;
    }
    .ql-btn.call { background: rgba(16,42,67,0.08); color: var(--navy); }
    .ql-btn.call:hover { background: var(--navy); color: #fff; }
    .ql-btn.whatsapp { background: rgba(37,211,102,0.1); color: #25d366; }
    .ql-btn.whatsapp:hover { background: #25d366; color: #fff; }

    @media (max-width: 900px) {
        .contact-grid { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
        .quick-lawyers { grid-template-columns: 1fr; }
        .contact-form-card { padding: 30px 25px; }
    }
</style>
@endpush

@section('content')

<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-phone-alt" style="color:var(--gold-main);margin-left:12px;"></i>تماس با ما</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <span>تماس با ما</span>
        </div>
    </div>
</div>

<div class="contact-page">

    <div class="contact-grid">

        {{-- ─── ستون اطلاعات ─────────────────────────────────── --}}
        <div class="info-col">

            <div class="info-box">
                <div class="info-box-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="info-box-text">
                    <h4>آدرس دفتر</h4>
                    <p>اصفهان، میدان جمهوری، جنب کلانتری ۱۲،<br>ساختمان جمهوری، طبقه اول، واحد ۴</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-box-icon"><i class="fas fa-phone"></i></div>
                <div class="info-box-text">
                    <h4>شماره‌های تماس</h4>
                    <p>
                        <a href="tel:09131146888">۰۹۱۳۱۱۴۶۸۸۸</a> — بابک ابدالی<br>
                        <a href="tel:09132888859">۰۹۱۳۲۸۸۸۸۵۹</a> — زهرا جوشقانی
                    </p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-box-icon"><i class="fas fa-envelope"></i></div>
                <div class="info-box-text">
                    <h4>ایمیل</h4>
                    <p><a href="mailto:info@abdali-law.ir">info@abdali-law.ir</a></p>
                </div>
            </div>

            <div class="hours-card">
                <h4><i class="fas fa-clock"></i> ساعات کاری</h4>
                <div class="hour-row">
                    <span>شنبه تا چهارشنبه</span>
                    <span>۱۷:۰۰ – ۲۱:۰۰ <span class="badge-open">باز</span></span>
                </div>
                <div class="hour-row">
                    <span>پنج‌شنبه</span>
                    <span><span class="badge-by-appointment">با وقت قبلی</span></span>
                </div>
                <div class="hour-row">
                    <span>جمعه</span>
                    <span style="color:rgba(255,255,255,0.4);">تعطیل</span>
                </div>
            </div>

            <div>
                <p style="font-size:0.85rem;color:var(--text-body);font-weight:600;margin-bottom:12px;">ما را در شبکه‌های اجتماعی دنبال کنید:</p>
                <div class="social-links">
                    <a href="#" class="social-btn instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn telegram"><i class="fab fa-telegram"></i></a>
                    <a href="https://wa.me/989131146888" target="_blank" class="social-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

        </div>

        {{-- ─── فرم تماس ──────────────────────────────────────── --}}
        <div class="contact-form-card">
            <h2>درخواست مشاوره رایگان</h2>
            <p>فرم زیر را تکمیل کنید. در اسرع وقت با شما تماس خواهیم گرفت.</p>

            @if(session('success'))
                <div style="background:rgba(16,185,129,0.1);border:1px solid #10b981;border-radius:10px;padding:15px 20px;margin-bottom:25px;color:#10b981;font-weight:600;">
                    <i class="fas fa-check-circle" style="margin-left:8px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group-p">
                        <label class="form-label-p">نام و نام خانوادگی <span>*</span></label>
                        <input type="text" name="name" class="form-input-p"
                               placeholder="علی محمدی" required value="{{ old('name') }}">
                        @error('name') <small style="color:#e74c3c;">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group-p">
                        <label class="form-label-p">شماره تماس <span>*</span></label>
                        <input type="tel" name="phone" class="form-input-p"
                               placeholder="۰۹۱۲۳۴۵۶۷۸۹" required style="direction:ltr;text-align:right;"
                               value="{{ old('phone') }}">
                        @error('phone') <small style="color:#e74c3c;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-p">
                        <label class="form-label-p">حوزه درخواست</label>
                        <select name="service" class="form-input-p">
                            <option value="">انتخاب کنید...</option>
                            <option value="family">حقوق خانواده</option>
                            <option value="commercial">دعاوی تجاری</option>
                            <option value="real-estate">دعاوی ملکی</option>
                            <option value="criminal">دعاوی کیفری</option>
                            <option value="inheritance">ارث و ترکه</option>
                            <option value="other">سایر موارد</option>
                        </select>
                    </div>
                    <div class="form-group-p">
                        <label class="form-label-p">ایمیل (اختیاری)</label>
                        <input type="email" name="email" class="form-input-p"
                               placeholder="example@email.com" style="direction:ltr;text-align:right;"
                               value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group-p">
                    <label class="form-label-p">شرح مختصر موضوع</label>
                    <textarea name="message" class="form-input-p"
                              placeholder="لطفاً خلاصه‌ای از موضوع پرونده یا سوال حقوقی خود را بنویسید...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn-submit-p">
                    <i class="fas fa-paper-plane"></i>
                    ارسال درخواست مشاوره
                </button>
            </form>
        </div>

    </div>

    {{-- ─── نقشه ──────────────────────────────────────────────── --}}
    <div class="map-section">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3355.0!2d51.6746!3d32.6573!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzLCsDM5JzI2LjMiTiA1McKwNDAnMjguNiJF!5e0!3m2!1sfa!2sir!4v1234567890"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <div class="map-overlay">
            <h4><i class="fas fa-map-marker-alt" style="color:var(--gold-main);margin-left:6px;"></i>دفتر وکالت</h4>
            <p>اصفهان، میدان جمهوری، ساختمان جمهوری، طبقه اول، واحد ۴</p>
        </div>
    </div>

    {{-- ─── Quick Contact per Lawyer ──────────────────────────── --}}
    <div class="quick-lawyers">
        <div class="ql-card">
            <div class="ql-avatar">ب</div>
            <div class="ql-info">
                <h4>بابک ابدالی</h4>
                <p>دعاوی تجاری، ملکی و کیفری</p>
                <div class="ql-actions">
                    <a href="tel:09131146888" class="ql-btn call">
                        <i class="fas fa-phone"></i> ۰۹۱۳۱۱۴۶۸۸۸
                    </a>
                    <a href="https://wa.me/989131146888" target="_blank" class="ql-btn whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="ql-card">
            <div class="ql-avatar" style="background:linear-gradient(135deg,#4a2c1a,#7a4a2e);">ز</div>
            <div class="ql-info">
                <h4>زهرا جوشقانی</h4>
                <p>حقوق خانواده، ارث و احوال شخصیه</p>
                <div class="ql-actions">
                    <a href="tel:09132888859" class="ql-btn call">
                        <i class="fas fa-phone"></i> ۰۹۱۳۲۸۸۸۸۵۹
                    </a>
                    <a href="https://wa.me/989132888859" target="_blank" class="ql-btn whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
