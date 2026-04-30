<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود وکلا | دفتر وکالت ابدالی و جوشقانی</title>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <style>
        :root {
            --gold-main: #d4af37;
            --gold-dark: #aa8222;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --bg-body: #0a0f1e;
            --shadow-card: 0 25px 60px rgba(0,0,0,0.4);
            --radius-md: 16px;
            --transition: all 0.3s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background: var(--bg-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(212,175,55,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(30,41,59,0.8) 0%, transparent 50%);
            z-index: 0;
        }

        .bg-scales {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .bg-scales i {
            font-size: 35rem;
            color: rgba(212,175,55,0.03);
            animation: slowSpin 60s linear infinite;
        }
        @keyframes slowSpin { 100% { transform: rotate(360deg); } }

        .page-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.4);
            font-size: 0.88rem;
            text-decoration: none;
            margin-bottom: 30px;
            transition: 0.3s;
        }
        .back-link:hover { color: var(--gold-main); }

        .auth-card {
            background: rgba(15,23,42,0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212,175,55,0.15);
            border-radius: 24px;
            padding: 50px 45px;
            box-shadow: var(--shadow-card);
            position: relative;
            overflow: hidden;
        }
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--gold-main), var(--gold-dark));
        }

        .logo-area { text-align: center; margin-bottom: 40px; }
        .logo-icon {
            width: 80px; height: 80px;
            background: rgba(212,175,55,0.1);
            border: 2px solid rgba(212,175,55,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: var(--gold-main);
        }
        .logo-area h1 { font-size: 1.5rem; font-weight: 900; color: #fff; margin-bottom: 6px; }
        .logo-area p { color: rgba(255,255,255,0.4); font-size: 0.85rem; }

        .form-group { margin-bottom: 22px; }
        .form-label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.88rem;
            color: rgba(255,255,255,0.7);
            font-weight: 600;
        }

        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.2);
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .input-wrapper:focus-within .input-icon { color: var(--gold-main); }

        .form-input {
            width: 100%;
            padding: 14px 44px 14px 16px;
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            color: #fff;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.95rem;
            transition: var(--transition);
            direction: ltr;
            text-align: right;
        }
        .form-input::placeholder { color: rgba(255,255,255,0.25); }
        .form-input:focus {
            outline: none;
            border-color: var(--gold-main);
            background: rgba(212,175,55,0.06);
            box-shadow: 0 0 0 3px rgba(212,175,55,0.1);
        }
        .form-input.is-error { border-color: #ef4444; }

        .error-msg { color: #f87171; font-size: 0.8rem; margin-top: 6px; display: block; }

        .alert-box {
            padding: 13px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.87rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: var(--navy);
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Vazirmatn', sans-serif;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(212,175,55,0.2);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(212,175,55,0.35);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 28px 0;
            color: rgba(255,255,255,0.15);
            font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }

        .security-note {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(212,175,55,0.05);
            border: 1px solid rgba(212,175,55,0.1);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
            margin-top: 28px;
        }
        .security-note i { color: var(--gold-main); flex-shrink: 0; }
    </style>
</head>
<body>

<div class="bg-scales">
    <i class="fas fa-scale-balanced"></i>
</div>

<div class="page-wrapper">
    <a href="{{ route('login') }}" class="back-link">
        <i class="fas fa-arrow-right"></i> بازگشت به ورود کاربران
    </a>

    <div class="auth-card">
        <div class="logo-area">
            <div class="logo-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <h1>پورتال اختصاصی وکلا</h1>
            <p>دفتر وکالت ابدالی و جوشقانی</p>
        </div>

        @if ($errors->any())
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.lawyer') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">ایمیل سازمانی</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input
                        type="email"
                        name="email"
                        class="form-input @error('email') is-error @enderror"
                        placeholder="lawyer@office.ir"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">رمز عبور</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        type="password"
                        name="password"
                        class="form-input @error('password') is-error @enderror"
                        placeholder="••••••••"
                        required
                    >
                </div>
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                <label style="display:flex; align-items:center; gap:8px; color:rgba(255,255,255,0.5); font-size:0.85rem; cursor:pointer;">
                    <input type="checkbox" name="remember" style="accent-color:var(--gold-main);">
                    مرا به خاطر بسپار
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i>
                ورود به پنل وکیل
            </button>
        </form>

        <div class="security-note">
            <i class="fas fa-shield-alt"></i>
            <span>دسترسی به این بخش فقط برای وکلای رسمی دفتر مجاز است. تمام فعالیت‌ها ثبت می‌شوند.</span>
        </div>
    </div>
</div>

</body>
</html>