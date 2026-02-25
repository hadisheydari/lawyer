<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام | دفتر وکالت ابدالی و جوشقانی</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #fdfbf7;
            --gold-main: #c5a059;
            --gold-dark: #9e7f41;
            --navy: #102a43;
            --text-heading: #2c241b;
            --text-body: #595048;
            --shadow-card: 0 15px 40px rgba(0,0,0,0.08);
            --radius-md: 12px;
            --transition: all 0.3s ease;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }
        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374z' fill='%23c5a059' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1;
        }
        .auth-container { width: 100%; max-width: 480px; }
        .auth-card {
            background: #fff;
            padding: 40px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            border-top: 5px solid var(--gold-main);
        }
        .logo-area { text-align: center; margin-bottom: 30px; }
        .logo-area i { font-size: 2.5rem; color: var(--gold-main); display: block; margin-bottom: 10px; }
        .logo-area h1 { font-size: 1.4rem; color: var(--navy); font-weight: 800; margin: 0 0 5px; }
        .logo-area p { font-size: 0.85rem; color: #999; margin: 0; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; margin-bottom: 7px; font-size: 0.88rem; color: var(--navy); font-weight: 600; }
        .optional { font-weight: 400; color: #aaa; font-size: 0.8rem; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 0.85rem; }
        .form-input {
            width: 100%;
            padding: 11px 38px 11px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.92rem;
            transition: var(--transition);
            background: #fcfcfc;
        }
        .form-input:focus { border-color: var(--gold-main); background: #fff; outline: none; box-shadow: 0 0 0 3px rgba(197,160,89,0.12); }
        .form-input.is-error { border-color: #ef4444; }
        .btn-primary {
            width: 100%; padding: 14px;
            background: var(--navy); color: #fff;
            border: none; border-radius: 8px;
            font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: var(--transition);
            font-family: 'Vazirmatn', sans-serif; margin-top: 5px;
        }
        .btn-primary:hover { background: #0a1c2e; transform: translateY(-2px); }
        .error-msg { color: #e74c3c; font-size: 0.8rem; margin-top: 4px; display: block; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.88rem; }
        .terms-note { font-size: 0.82rem; color: #999; text-align: center; margin-top: 15px; line-height: 1.7; }
        .terms-note a { color: var(--gold-dark); text-decoration: none; }
        .auth-footer { margin-top: 20px; font-size: 0.9rem; color: #999; text-align: center; }
        .auth-footer a { color: var(--gold-dark); font-weight: 700; text-decoration: none; }
        .home-link { display: block; text-align: center; margin-top: 20px; color: var(--navy); font-size: 0.88rem; font-weight: 600; opacity: 0.6; transition: 0.3s; text-decoration: none; }
        .home-link:hover { opacity: 1; color: var(--gold-main); }
    </style>
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <div class="logo-area">
            <i class="fas fa-user-plus"></i>
            <h1>ثبت نام</h1>
            <p>ساخت حساب کاربری جدید</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">نام</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="first_name"
                               class="form-input @error('first_name') is-error @enderror"
                               placeholder="نام" value="{{ old('first_name') }}" required>
                    </div>
                    @error('first_name')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">نام خانوادگی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="last_name"
                               class="form-input @error('last_name') is-error @enderror"
                               placeholder="نام خانوادگی" value="{{ old('last_name') }}" required>
                    </div>
                    @error('last_name')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">شماره موبایل</label>
                <div class="input-wrapper">
                    <i class="fas fa-mobile-alt input-icon"></i>
                    <input type="tel" name="phone"
                           class="form-input @error('phone') is-error @enderror"
                           placeholder="09123456789"
                           value="{{ old('phone') }}" maxlength="11" required
                           style="direction:ltr;text-align:right;">
                </div>
                @error('phone')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    کد ملی <span class="optional">(اختیاری)</span>
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-id-card input-icon"></i>
                    <input type="text" name="national_code"
                           class="form-input @error('national_code') is-error @enderror"
                           placeholder="کد ملی ۱۰ رقمی"
                           value="{{ old('national_code') }}" maxlength="10"
                           style="direction:ltr;text-align:right;">
                </div>
                @error('national_code')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    ایمیل <span class="optional">(اختیاری)</span>
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email"
                           class="form-input @error('email') is-error @enderror"
                           placeholder="example@email.com"
                           value="{{ old('email') }}"
                           style="direction:ltr;text-align:right;">
                </div>
                @error('email')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-user-plus"></i> ثبت نام و دریافت کد تأیید
            </button>
        </form>

        <p class="terms-note">
            با ثبت نام، <a href="#">شرایط استفاده</a> و <a href="#">سیاست حریم خصوصی</a> را می‌پذیرید.
        </p>
        <div class="auth-footer">
            قبلاً ثبت نام کرده‌اید؟ <a href="{{ route('login') }}">وارد شوید</a>
        </div>
    </div>

    <a href="{{ route('home') }}" class="home-link">
        <i class="fas fa-arrow-right"></i> بازگشت به صفحه اصلی
    </a>
</div>
</body>
</html>
