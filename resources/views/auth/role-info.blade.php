<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت اطلاعات نقش</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen bg-gradient-to-r from-sky-900 via-blue-950 to-slate-900 flex items-center justify-center">
    <div class="bg-blue-100 rounded-lg shadow-lg w-[95vw]  m-[4vh] p-6">
        @if(auth()->check() && auth()->user()->hasRole('company'))

            <x-role-info.company-role :cities="$cities" />

        @elseif(auth()->check() && auth()->user()->hasRole('driver'))

            <x-role-info.driver-role :cities="$cities" :companies="$companies" />

        @elseif(auth()->check() && auth()->user()->hasRole('productOwner') && session('user_type') === 'real')

            <x-role-info.product-owner-real-role :cities="$cities" />

        @elseif(auth()->check() && auth()->user()->hasRole('productOwner') && session('user_type') === 'legal')

            <x-role-info.product-owner-legal-role :cities="$cities" />

        @else
            <p>نقشی تنظیم نشده</p>
        @endif
    </div>
</div>
</body>

<script src="{{asset('assets/js/dependencies/plugins.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('datepicker inputs:', $('.datepicker').length);
        $('.datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValueType: 'gregorian'
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ownershipSelect = document.querySelector('select[name="property"]');
        const companyField = document.getElementById('company-field');
        if (ownershipSelect) {
            ownershipSelect.addEventListener('change', function () {
                if (this.value === 'owned') {
                    companyField.classList.remove('hidden');
                } else {
                    companyField.classList.add('hidden');
                }
            });
            if (ownershipSelect.value === 'owned') {
                companyField.classList.remove('hidden');
            }
        }
    });
</script>
</html>
