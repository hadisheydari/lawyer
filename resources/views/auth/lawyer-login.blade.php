<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به پورتال وکلا | دفتر حقوقی</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <style>
        :root {
            --navy: #102a43;
            --navy-dark: #0a1c2e;
            --gold: #c5a059;
            --gold-dark: #9e7f41;
            --text-gray: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Vazirmatn', sans-serif; }

        body {
            background: var(--navy-dark);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* پس‌زمینه تزئینی */
        body::before {
            content: ""; position: absolute; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(197, 160, 89, 0.1) 0%, transparent 70%);
            top: -200px; right: -200px; border-radius: 50%;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            border-radius: 25px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 10;
            text-align: center;
            border-top: 5px solid var(--gold);
        }

        .login-icon {
            width: 70px; height: 70px; background: rgba(197, 160, 89, 0.1);
            border-radius: 20px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 25px; font-size: 2rem; color: var(--gold-dark);
        }

        h1 { color: var(--navy); font-size: 1.5rem; font-weight: 900; margin-bottom: 10px; }
        p { color: var(--text-gray); font-size: 0.9rem; margin-bottom: 35px; }

        .form-group { margin-bottom: 22px; text-align: right; position: relative; }
        
        .form-group i {
            position: absolute; right: 18px; top: 48px;
            color: #cbd5e1; font-size: 1.1rem; transition: 0.3s;
        }

        label { display: block; font-size: 0.85rem; font-weight: 800; color: var(--navy); margin-bottom: 10px; }

        .form-input {
            width: 100%; padding: 16px 50px 16px 18px;
            border: 2px solid #e2e8f0; border-radius: 14px;
            font-size: 1rem; color: var(--navy);
            background: #f8fafc; transition: all 0.3s ease; outline: none;
        }

        .form-input:focus {
            border-color: var(--gold); background: #fff;
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.1);
        }

        .form-input:focus + i { color: var(--gold); }

        .error-msg { color: #ef4444; font-size: 0.8rem; font-weight: 700; margin-top: 6px; display: block; }

        .options {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; font-size: 0.85rem; color: var(--text-gray);
        }

        .btn-login {
            width: 100%; padding: 18px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: #fff; border: none; border-radius: 14px;
            font-size: 1.1rem; font-weight: 800; cursor: pointer;
            transition: 0.3s; box-shadow: 0 10px 25px rgba(197, 160, 89, 0.3);
        }

        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(197, 160, 89, 0.5); }

        .back-link {
            display: inline-block; margin-top: 30px; color: var(--text-gray);
            text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: 0.2s;
        }
        .back-link:hover { color: var(--navy); }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <h1>ورود وکلا</h1>
        <p>لطفاً شماره موبایل و رمز عبور خود را وارد کنید.</p>

        <form action="{{ route('lawyer.login.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>شماره موبایل</label>
                <input type="tel" name="phone" class="form-input" placeholder="0912xxxxxxx" 
                       value="{{ old('phone') }}" required dir="ltr" style="text-align: right;">
                <i class="fas fa-mobile-alt"></i>
                @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>رمز عبور</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required dir="ltr" style="text-align: right;">
                <i class="fas fa-lock"></i>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="options">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="remember" style="accent-color: var(--gold); width: 17px; height: 17px;"> مرا به خاطر بسپار
                </label>
                <a href="#" style="color: var(--gold-dark); text-decoration: none; font-weight: 700;">فراموشی رمز؟</a>
            </div>

            <button type="submit" class="btn-login">ورود به پنل مدیریت</button>
        </form>

        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-right"></i> بازگشت به صفحه اصلی سایت
        </a>
    </div>

</body>
</html>