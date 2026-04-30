@extends('layouts.client')

@section('title', 'پروفایل کاربری')

@push('styles')
<style>
    .profile-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
        align-items: start;
    }

    .profile-card {
        background: #fff;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-card);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .pc-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-dark) 100%);
        padding: 40px 20px;
        text-align: center;
        color: #fff;
        position: relative;
    }
    
    .pc-header::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 5px;
        background: linear-gradient(90deg, var(--gold-main), var(--gold-light));
    }

    .pc-avatar {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border: 2px solid var(--gold-main);
        border-radius: 50%;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 900;
        color: var(--gold-light);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .pc-header h3 { font-size: 1.3rem; font-weight: 800; margin-bottom: 5px; }
    .pc-header p { color: rgba(255,255,255,0.7); font-size: 0.9rem; margin-bottom: 10px;}

    .pc-body { padding: 30px; }

    .info-list { list-style: none; padding: 0; margin: 0; }
    .info-list li {
        display: flex; justify-content: space-between; align-items: center;
        padding: 15px 0; border-bottom: 1px dashed #eee; font-size: 0.95rem;
    }
    .info-list li:last-child { border-bottom: none; }
    .info-list li .label { color: var(--text-body); font-weight: 600; display: flex; align-items: center; gap: 8px;}
    .info-list li .label i { color: var(--gold-dark); width: 20px; text-align: center;}
    .info-list li .value { color: var(--navy); font-weight: 800; }

    /* Form Styles */
    .form-section-title {
        font-size: 1.2rem; font-weight: 800; color: var(--navy);
        margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f5f0ea;
        display: flex; align-items: center; gap: 10px;
    }
    .form-section-title i { color: var(--gold-main); }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    
    .input-box {
        position: relative; display: flex; align-items: center;
        background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px;
        padding: 0 15px; transition: var(--transition);
    }
    .input-box:focus-within {
        border-color: var(--gold-main); background: #fff;
        box-shadow: 0 5px 15px rgba(207, 168, 110, 0.1);
    }
    .input-box i { color: #94a3b8; font-size: 1.1rem; margin-left: 10px; transition: var(--transition); }
    .input-box:focus-within i { color: var(--gold-main); }
    
    .input-box input {
        flex: 1; border: none; background: transparent; padding: 15px 0;
        font-family: inherit; font-size: 0.95rem; color: var(--text-heading); outline: none;
    }
    .input-box input:disabled { color: #94a3b8; cursor: not-allowed; }

    .form-label { display: block; font-size: 0.9rem; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
    .form-error { color: var(--danger); font-size: 0.8rem; font-weight: 600; margin-top: 5px; display: flex; align-items: center; gap: 5px;}

    .btn-submit {
        background: linear-gradient(135deg, var(--navy), var(--navy-dark));
        color: #fff; border: none; padding: 15px 30px; border-radius: 10px;
        font-family: inherit; font-weight: 800; font-size: 1rem; cursor: pointer;
        display: inline-flex; align-items: center; gap: 10px; transition: var(--transition);
        box-shadow: 0 10px 20px rgba(10, 28, 46, 0.2);
    }
    .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(10, 28, 46, 0.3); }

    @media (max-width: 900px) {
        .profile-container { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="profile-container">

    {{-- ستون راست: اطلاعات کلی --}}
    <div class="profile-card">
        <div class="pc-header">
            <div class="pc-avatar">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <h3>{{ $user->name }}</h3>
            <p>{{ $user->phone }}</p>
            @if($user->isSpecial())
                <span class="vip-badge" style="display:inline-flex; margin-top:10px;"><i class="fas fa-crown"></i> موکل ویژه دفتر</span>
            @else
                <span style="background:rgba(255,255,255,0.2); padding: 4px 12px; border-radius:20px; font-size:0.8rem;">کاربر عادی</span>
            @endif
        </div>
        <div class="pc-body">
            <ul class="info-list">
                <li>
                    <span class="label"><i class="fas fa-mobile-alt"></i> شماره موبایل</span>
                    <span class="value" dir="ltr">{{ $user->phone }}</span>
                </li>
                <li>
                    <span class="label"><i class="far fa-id-card"></i> کد ملی</span>
                    <span class="value">{{ $user->national_code ?? 'ثبت نشده' }}</span>
                </li>
                <li>
                    <span class="label"><i class="far fa-envelope"></i> ایمیل</span>
                    <span class="value">{{ $user->email ?? 'ثبت نشده' }}</span>
                </li>
                <li>
                    <span class="label"><i class="far fa-calendar-alt"></i> تاریخ عضویت</span>
                    <span class="value">{{ \Morilog\Jalali\Jalalian::fromCarbon($user->created_at)->format('Y/m/d') }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- ستون چپ: فرم ویرایش --}}
    <div class="profile-card">
        <div class="pc-body" style="padding: 40px;">
            <h3 class="form-section-title"><i class="fas fa-user-edit"></i> ویرایش اطلاعات هویتی</h3>
            
            <form action="{{ route('client.profile.update') }}" method="POST">
                @csrf
                @method('PUT') {{-- معمولا برای آپدیت از PUT استفاده میشه، مطمئن شو روتِت درسته --}}

                <div class="form-row">
                    <div>
                        <label class="form-label">نام و نام خانوادگی</label>
                        <div class="input-box">
                            <i class="far fa-user"></i>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name')<span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">شماره موبایل (غیرقابل تغییر)</label>
                        <div class="input-box" style="background: #f1f5f9;">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="text" value="{{ $user->phone }}" disabled dir="ltr" style="text-align: right;">
                        </div>
                        <small style="color:#94a3b8; font-size:0.75rem; margin-top:5px; display:block;">برای تغییر شماره تماس با پشتیبانی ارتباط بگیرید.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label class="form-label">کد ملی</label>
                        <div class="input-box">
                            <i class="far fa-id-card"></i>
                            <input type="text" name="national_code" value="{{ old('national_code', $user->national_code) }}" placeholder="کد ملی ۱۰ رقمی" dir="ltr" style="text-align: right;">
                        </div>
                        @error('national_code')<span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">پست الکترونیک</label>
                        <div class="input-box">
                            <i class="far fa-envelope"></i>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="example@mail.com" dir="ltr" style="text-align: right;">
                        </div>
                        @error('email')<span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                    </div>
                </div>

                <div style="margin-top: 35px; text-align: left;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> ذخیره تغییرات
                    </button>
                </div>
            </form>
            
        </div>
    </div>

</div>

@endsection