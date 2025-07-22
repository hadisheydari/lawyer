@extends('layouts.auth')
@section('title', 'ثبت نام در بازارگاه')
@section('header', 'ثبت نام در بازارگاه')

@section('content')

    <x-form.button name="RoleInfo"
                   action="window.location.href='{{ route('selectRoleAction', ['role' => 'company']) }}'"
                   text="شرکت حمل" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-building2-icon lucide-building-2">
            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
            <path d="M10 6h4"/>
            <path d="M10 10h4"/>
            <path d="M10 14h4"/>
            <path d="M10 18h4"/>
        </svg>
    </x-form.button>
    <x-form.button name="RoleInfo" action="openProductOwnerModal(this)" text="صاحب کالا" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-circle-user-round-icon lucide-circle-user-round">
            <path d="M18 20a6 6 0 0 0-12 0"/>
            <circle cx="12" cy="10" r="4"/>
            <circle cx="12" cy="12" r="10"/>
        </svg>
    </x-form.button>
    <x-form.button name="RoleInfo" action="window.location.href='{{ route('selectRoleAction', ['role' => 'driver']) }}'"
                   text="راننده" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-car-front-icon lucide-car-front">
            <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"/>
            <path d="M7 14h.01"/>
            <path d="M17 14h.01"/>
            <rect width="18" height="8" x="3" y="10" rx="2"/>
            <path d="M5 18v2"/>
            <path d="M19 18v2"/>
        </svg>
    </x-form.button>
    <div id="selectRoleAction" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm z-50 hidden">
        <div class="bg-white w-10/12 md:w-8/12 lg:w-6/12 mx-auto mt-24 p-6 rounded-lg shadow-lg relative">
            <button id="modalCloseBtn" class="absolute top-4 right-4 text-2xl text-gray-700 ">
                <i class="fa fa-close"></i>
            </button>
            <div class="text-blue-800 font-black text-2xl m-12 ">
                لطفا مشخص کنید که شخص حقیقی هستید یا حقوقی
            </div>
            <x-form.button name="RoleInfo"
                           action="window.location.href='{{ route('selectRoleAction', ['role' => 'productOwner', 'type' => 'real']) }}'"
                           text=" صاحب کالا حقیقی" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-user-round-check-icon lucide-user-round-check">
                    <path d="M2 21a8 8 0 0 1 13.292-6"/>
                    <circle cx="10" cy="8" r="5"/>
                    <path d="m16 19 2 2 4-4"/>
                </svg>
            </x-form.button>

            <x-form.button name="RoleInfo"
                           action="window.location.href='{{ route('selectRoleAction', ['role' => 'productOwner', 'type' => 'legal']) }}'"
                           text=" صاحب کالا حقوقی" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-user-lock-icon lucide-user-lock">
                    <circle cx="10" cy="7" r="4"/>
                    <path d="M10.3 15H7a4 4 0 0 0-4 4v2"/>
                    <path d="M15 15.5V14a2 2 0 0 1 4 0v1.5"/>
                    <rect width="8" height="5" x="13" y="16" rx=".899"/>
                </svg>
            </x-form.button>

        </div>
    </div>

@endsection
@section('script')
    <script>
        function openProductOwnerModal(button) {
            const modal = document.getElementById('selectRoleAction');
            modal.classList.remove('hidden');
        }

        document.getElementById('modalCloseBtn').addEventListener('click', function () {
            document.getElementById('selectRoleAction').classList.add('hidden');
        });
    </script>
@endsection
