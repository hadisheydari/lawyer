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
    <title>   @yield('title')  {{ config('app.market_name') }}  </title>
</head>
<body>
<div class="min-h-screen bg-gradient-to-r from-sky-900 via-blue-950 to-slate-900 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-[70vw] m-[10vh] p-6 flex flex-col items-center justify-start overflow-hidden">

        <div class="text-blue-700 font-black text-5xl m-12 ">
                 @yield('header') {{ config('app.market_name') }}
        </div>

        <div class="flex flex-row w-full flex-1 items-center justify-center overflow-hidden">
            <div class="basis-1/2 mr-14">
                @yield('content')
            </div>
            <div class="basis-1/2 m-24">
                <img src="img/Auth/authImg.webp" alt="">
            </div>
        </div>

    </div>
</div>

</body>
    @yield('script')
</html>
