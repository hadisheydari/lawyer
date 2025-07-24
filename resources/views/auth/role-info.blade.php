<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت اطلاعات نقش</title>

    @vite(['resources/css/app.css', 'resources/js/app.js' , 'resources/js/methods/ownership-toggle.js' ])
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen bg-gradient-to-r from-sky-900 via-blue-950 to-slate-900 flex items-center justify-center">
    <div class="bg-blue-100 rounded-lg shadow-lg w-[95vw]  m-[4vh] p-6">
        @if(auth()->check() && auth()->user()->hasRole('company'))

            <x-role-info.company-role :cities="$cities" >
                <div class="flex items-center m-4">
                    <input name="conditions" id="link-checkbox" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500  focus:ring-2 ">
                    <label for="link-checkbox" class="ms-2 text-sm font-medium text-gray-900"> <a href="#" class="text-blue-600 hover:underline">با قوانین و مقررات </a>موافقت میکنم .</label>
                </div>
            </x-role-info.company-role>

            @elseif(auth()->check() && auth()->user()->hasRole('driver'))

            <x-role-info.driver-role :cities="$cities" :companies="$companies" >
                <div class="flex items-center m-4">
                    <input name="conditions" id="link-checkbox" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500  focus:ring-2 ">
                    <label for="link-checkbox" class="ms-2 text-sm font-medium text-gray-900"> <a href="#" class="text-blue-600 hover:underline">با قوانین و مقررات </a>موافقت میکنم .</label>
                </div>
            </x-role-info.driver-role>

        @elseif(auth()->check() && auth()->user()->hasRole('productOwner') && session('user_type') === 'real')

            <x-role-info.product-owner-real-role :cities="$cities" >
                <div class="flex items-center m-4">
                    <input name="conditions" id="link-checkbox" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500  focus:ring-2 ">
                    <label for="link-checkbox" class="ms-2 text-sm font-medium text-gray-900"> <a href="#" class="text-blue-600 hover:underline">با قوانین و مقررات </a>موافقت میکنم .</label>
                </div>
            </x-role-info.product-owner-real-role>

        @elseif(auth()->check() && auth()->user()->hasRole('productOwner') && session('user_type') === 'legal')

            <x-role-info.product-owner-legal-role :cities="$cities" >
                <div class="flex items-center m-4">
                    <input name="conditions" id="link-checkbox" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500  focus:ring-2 ">
                    <label for="link-checkbox" class="ms-2 text-sm font-medium text-gray-900"> <a href="#" class="text-blue-600 hover:underline">با قوانین و مقررات </a>موافقت میکنم .</label>
                </div>
            </x-role-info.product-owner-legal-role>

        @else
            <p>نقشی تنظیم نشده</p>
        @endif
    </div>
</div>
</body>


</html>
