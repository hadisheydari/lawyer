@extends('layouts.auth')
@section('title', 'ثبت نام در ')
@section('header', 'ثبت نام در ')

@section('content')
    <x-form.base-form action="{{ route('registerAction') }}" method="POST" class="space-y-6">
        @csrf

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 text-red-700 text-sm font-medium space-y-1">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <x-form.input
                name="name"
                label="نام و نام خانوادگی"
                type="text"
                placeholder="جواد ذاکر"
                value="{{ old('name') }}"
            />

        </div>

        <div>
            <x-form.input
                name="phone"
                label="شماره تلفن"
                type="text"
                placeholder="09xxxxxxxxx"
                value="{{ old('phone') }}"
            />

        </div>

        <div>
            <x-form.input-password
                id="password"
                name="password"
                label="گذرواژه"
                placeholder="********"
                minlength="8"
            />

        </div>

        <div>
            <x-form.input-password
                id="repetPassword"
                name="password_confirmation"
                label="تکرار گذرواژه"
                placeholder="********"
                minlength="8"
            />

        </div>

        <div>
            <x-form.button text="ثبت نام" type="submit" class="w-full"/>
        </div>

        <p class="mt-6 text-center text-gray-600 text-sm">
            قبلا ثبت نام کرده اید؟
            <a href="{{ route('login') }}"
               class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                ورود
            </a>
        </p>
    </x-form.base-form>
@endsection

@section('script')
    <script>
        document.querySelector('form').addEventListener('submit', function (event) {
            const pass1 = document.getElementById('password').value;
            const pass2 = document.getElementById('repetPassword').value;

            if (pass1 !== pass2) {
                event.preventDefault();
                alert('گذرواژه یکسان نیست.');
            }
        });
    </script>

@endsection


