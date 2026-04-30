@extends('layouts.public') {{-- یا یک لی‌اوت ساده --}}
@section('title', 'ورود وکلا')

@section('content')
<div style="max-width: 400px; margin: 100px auto; padding: 30px; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
    <i class="fas fa-user-shield" style="font-size: 3rem; color: #c5a059; margin-bottom: 20px;"></i>
    <h2 style="margin-bottom: 10px;">ورود به پنل وکلا</h2>
    
    @if(!session('lawyer_otp_phone'))
        {{-- مرحله اول: دریافت شماره --}}
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 25px;">شماره موبایل خود را وارد کنید.</p>
        <form action="{{ route('lawyer.send-otp') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px; text-align: right;">
                <input type="tel" name="phone" placeholder="09123456789" required style="width: 100%; padding: 15px; border: 2px solid #eee; border-radius: 12px; text-align: center; direction: ltr;">
                @error('phone') <small style="color: red;">{{ $message }}</small> @enderror
            </div>
            <button type="submit" style="width: 100%; padding: 15px; background: #102a43; color: #fff; border: none; border-radius: 12px; font-weight: bold; cursor: pointer;">ارسال کد تایید</button>
        </form>
    @else
        {{-- مرحله دوم: دریافت کد --}}
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 25px;">کد ارسال شده به {{ session('lawyer_otp_phone') }} را وارد کنید.</p>
        <form action="{{ route('lawyer.verify-otp') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <input type="number" name="code" placeholder="------" required style="width: 100%; padding: 15px; border: 2px solid #eee; border-radius: 12px; text-align: center; letter-spacing: 10px; font-size: 1.2rem;">
                @error('code') <small style="color: red;">{{ $message }}</small> @enderror
            </div>
            <button type="submit" style="width: 100%; padding: 15px; background: #c5a059; color: #fff; border: none; border-radius: 12px; font-weight: bold; cursor: pointer;">تایید و ورود</button>
        </form>
    @endif

    <a href="{{ route('home') }}" style="display: block; margin-top: 20px; color: #999; text-decoration: none; font-size: 0.8rem;">بازگشت به صفحه اصلی</a>
</div>
@endsection