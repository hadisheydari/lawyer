@extends('layouts.auth')
@section('title', 'ورود به ')
@section('header', 'ورود به ')

@section('content')

    <x-form.base-form action="{{ route('verifyAction') }}" method="POST">
        @if (session('status'))
            <p class="mb-4 text-sm text-green-600">{{ session('status') }}</p>
        @endif
        <x-form.input name="code" label="کد تایید هویت" type="text" placeholder="********" maxlength="6" required/>

        <x-form.button text="ثبت نام" type="submit"/>


    </x-form.base-form>

@endsection
