<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به پورتال | دفتر وکالت ابدالی و جوشقانی</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #fdfbf7;
            --gold-main: #c5a059;
            --gold-dark: #9e7f41;
            --navy: #102a43;
            --text-gray: #555555;
            --shadow-card: 0 15px 40px rgba(0,0,0,0.08);
            --radius-md: 12px;
            --transition: all 0.3s ease;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-gray);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374z' fill='%23c5a059' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1;
        }
        .auth-container { width: 100%; max-width: 420px; padding: 20px; }
        .auth-card {
            background: #fff;
            padding: 40px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            border-top: 5px solid var(--gold-main);
            text-align: center;
        }
        .logo-area { margin-bottom: 30px; }
        .logo-area i { font-size: 3rem; color: var(--gold-main); display: block; margin-bottom: 10px; }
        .logo-area h1 { font-size: 1.4rem; color: var(--navy); font-weight: 800; margin: 0 0 5px; }
        .logo-area p { font-size: 0.85rem; color: #999; margin: 0; }
        .form-group { margin-bottom: 20px; text-align: right; }
        .form-label { display: block; margin-bottom: 8px; font-size: 0.9rem; color: var(--navy); font-weight: 600; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 0.9rem; }
        .form-input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.95rem;
            transition: var(--transition);
            background: #fcfcfc;
            direction: ltr;
            text-align: right;
        }
        .form-input:focus { border-color: var(--gold-main); background: #fff; outline: none; box-shadow: 0 0 0 3px rgba(197,160,89,0.12); }
        .otp-input { letter-spacing: 6px; font-size: 1.4rem; font-weight: 700; text-align: center; color: var(--navy); }
        .btn-primary {
            width: 100%; padding: 14px;
            background: var(--navy); color: #fff;
            border: none; border-radius: 8px;
            font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: var(--transition);
            font-family: 'Vazirmatn', sans-serif; margin-top: 5px;
        }
        .btn-primary:hover { background: #0a1c2e; transform: translateY(-2px); }
        .btn-outline {
            width: 100%; padding: 12px;
            background: transparent; color: var(--gold-dark);
            border: 1.5px solid var(--gold-main); border-radius: 8px;
            font-weight: 600; font-size: 0.9rem;
            cursor: pointer; transition: var(--transition);
            font-family: 'Vazirmatn', sans-serif; margin-top: 10px;
        }
        .btn-outline:hover { background: var(--gold-main); color: #fff; }
        .otp-timer { font-size: 0.85rem; color: #999; margin-top: 10px; text-align: center; }
        .otp-timer span { color: var(--gold-dark); font-weight: 700; }
        .step { display: none; }
        .step.active { display: block; }
        .phone-preview {
            background: #f5f5f5; border-radius: 8px;
            padding: 10px 15px; font-size: 0.9rem;
            color: var(--navy); font-weight: 600;
            margin-bottom: 20px; text-align: center;
            direction: ltr;
        }
        .auth-divider { display: flex; align-items: center; gap: 15px; margin: 20px 0; color: #ccc; font-size: 0.85rem; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: #eee; }
        .auth-footer { margin-top: 20px; font-size: 0.9rem; color: #999; }
        .auth-footer a { color: var(--gold-dark); font-weight: 700; text-decoration: none; }
        .home-link { display: block; text-align: center; margin-top: 20px; color: var(--navy); font-size: 0.88rem; font-weight: 600; opacity: 0.6; transition: 0.3s; text-decoration: none; }
        .home-link:hover { opacity: 1; color: var(--gold-main); }
        .error-msg { color: #e74c3c; font-size: 0.82rem; margin-top: 5px; display: block; }
        .alert-box { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.88rem; display: flex; align-items: center; gap: 8px; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-info  { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
    </style>
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <div class="logo-area">
            <i class="fas fa-scale-balanced"></i>
            <h1>ورود به پورتال</h1>
            <p>دفتر وکالت ابدالی و جوشقانی</p>
        </div>

        {{-- Alert ها --}}
        @if($errors->any())
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert-box alert-info">
                <i class="fas fa-info-circle"></i> {{ session('info') }}
            </div>
        @endif

        {{-- مرحله ۱: شماره موبایل --}}
        <div class="step {{ !session('otp_phone') ? 'active' : '' }}" id="stepPhone">
            <form method="POST" action="{{ route('auth.send-otp') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">شماره موبایل</label>
                    <div class="input-wrapper">
                        <i class="fas fa-mobile-alt input-icon"></i>
                        <input type="tel" name="phone" class="form-input"
                               placeholder="09123456789"
                               value="{{ old('phone') }}"
                               maxlength="11" required autofocus>
                    </div>
                    @error('phone')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> ارسال کد تأیید
                </button>
            </form>

            <div class="auth-divider">یا</div>
            <div class="auth-footer">
                حساب کاربری ندارید؟
                <a href="{{ route('register') }}">ثبت نام کنید</a>
            </div>
        </div>

        {{-- مرحله ۲: کد OTP --}}
        <div class="step {{ session('otp_phone') ? 'active' : '' }}" id="stepOtp">
            <div class="phone-preview">
                <i class="fas fa-mobile-alt" style="color:var(--gold-main);margin-left:5px;"></i>
                {{ session('otp_phone') }}
            </div>
            <p style="font-size:0.88rem;color:#999;margin-bottom:20px;text-align:center;">
                کد ۶ رقمی ارسال شده به شماره بالا را وارد کنید
            </p>

            <form method="POST" action="{{ route('auth.verify-otp') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">کد تأیید</label>
                    <input type="text" name="code" class="form-input otp-input"
                           placeholder="_ _ _ _ _ _" maxlength="6"
                           inputmode="numeric" autofocus required>
                    @error('code')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i> تأیید و ورود
                </button>
            </form>

            <div class="otp-timer" id="otpTimer">
                ارسال مجدد تا <span id="timerCount">۱۲۰</span> ثانیه دیگر
            </div>

            <form method="POST" action="{{ route('auth.send-otp') }}" id="resendForm" style="display:none;">
                @csrf
                <input type="hidden" name="phone" value="{{ session('otp_phone') }}">
                <button type="submit" class="btn-outline">
                    <i class="fas fa-redo"></i> ارسال مجدد کد
                </button>
            </form>

            <button onclick="clearOtpSession()" class="btn-outline" style="margin-top:8px;">
                <i class="fas fa-arrow-right"></i> تغییر شماره
            </button>

            <form id="clearForm" method="POST" action="{{ route('auth.clear-session') }}" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <a href="{{ route('home') }}" class="home-link">
        <i class="fas fa-arrow-right"></i> بازگشت به صفحه اصلی
    </a>
</div>

<script>
    @if(session('otp_phone'))
    let timeLeft = 120;
    const timerEl = document.getElementById('timerCount');
    const timerWrapper = document.getElementById('otpTimer');
    const resendForm = document.getElementById('resendForm');

    const d = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    const toPersian = n => String(n).split('').map(c => d[+c] ?? c).join('');

    const timer = setInterval(() => {
        timeLeft--;
        timerEl.textContent = toPersian(timeLeft);
        if (timeLeft <= 0) {
            clearInterval(timer);
            timerWrapper.style.display = 'none';
            resendForm.style.display = 'block';
        }
    }, 1000);
    @endif

    // فقط عدد
    const otpInput = document.querySelector('.otp-input');
    if (otpInput) {
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    function clearOtpSession() {
        document.getElementById('clearForm').submit();
    }
</script>
</body>
</html>
