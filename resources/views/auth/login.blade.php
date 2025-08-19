@extends('layouts.auth')
@section('title', 'ورود به ')
@section('header', 'ورود به ')

@section('content')

    <x-form.base-form action="{{ route('loginAction') }}" method="POST">

        @if ($errors->has('username-or-password-wrong'))
            <p class="mb-4 text-sm text-red-600">{{ $errors->first('username-or-password-wrong') }}</p>
        @endif

        @if (session('status'))
            <p class="mb-4 text-sm text-green-600">{{ session('status') }}</p>
        @endif

        <x-form.input name="phone" label="شماره تلفن" type="text" placeholder="09xxxxxxxxx" required/>

        <x-form.input-password name="password" label="گذرواژه" placeholder="********" minlength="8"/>

        <x-form.button text="ورود" type="submit"/>

        <p class="mt-6 text-sm text-center text-gray-600">
            حساب کاربری نداری؟
            <a href="{{ route('register') }}"
               class="text-blue-500 hover:text-blue-600 font-medium transition-colors duration-200">
                ثبت‌نام کن
            </a>
        </p>
    </x-form.base-form>

@endsection
