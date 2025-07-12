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

    <title>Document</title>
</head>
<body>
<x-auth-background text="ثبت نام در بازارگاه">
    <x-form.base-form action="#" method="POST" >
        <x-form.input name="name" label="نام کاربری" type="text" placeholder="نام کاربری" required />
        <x-form.input name="phone" label="شماره تلفن" type="text" placeholder="09xxxxxxxxx" required />
        <x-form.input-password name="password" label="گذرواژه" placeholder="********" minlength="8"/>
        <x-form.input-password name="repetPassword" label="تکرار گذرواژه" placeholder="********" minlength="8"/>

        <x-form.button text="ورود" type="submit"/>
        <p class="mt-6 text-sm text-center text-gray-600">
            قبلا ثبت نام کرده اید ؟
            <a href="/" class="text-blue-500 hover:text-blue-600 font-medium transition-colors duration-200">
                ورود
            </a>
        </p>

    </x-form.base-form>
</x-auth-background>
</body>
<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const pass1 = document.getElementById('password').value;
        const pass2 = document.getElementById('repetPassword').value;

        if (pass1 !== pass2) {
            event.preventDefault();
            alert('گذرواژه یکسان نیست .');
        }
    });



</script>
</html>
