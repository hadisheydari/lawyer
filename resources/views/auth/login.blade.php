<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>ورود به بازارگاه</title>
</head>
<body>
<x-auth-background text="ورود به بازارگاه">
    <x-form.base-form action="{{ route('loginAction') }}" method="POST">
        @csrf

        @if ($errors->has('username-or-password-wrong'))
            <p class="mb-4 text-sm text-red-600">{{ $errors->first('username-or-password-wrong') }}</p>
        @endif

        @if (session('status'))
            <p class="mb-4 text-sm text-green-600">{{ session('status') }}</p>
        @endif

        <x-form.input name="phone" label="شماره تلفن" type="text" placeholder="09xxxxxxxxx" required />
        @error('phone')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror

        <x-form.input-password name="password" label="گذرواژه" placeholder="********" minlength="8" />
        @error('password')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror

        <x-form.button text="ورود" type="submit" />

        <p class="mt-6 text-sm text-center text-gray-600">
            حساب کاربری نداری؟
            <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-600 font-medium transition-colors duration-200">
                ثبت‌نام کن
            </a>
        </p>
    </x-form.base-form>

</x-auth-background>

</body>
</html>
