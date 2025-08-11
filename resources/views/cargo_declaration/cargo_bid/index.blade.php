@extends('layouts.main')

@section('title', 'لیست بار ازاد ')
@section('header', 'لیست بار ازاد ')

@section('content')

    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    @if (session('error'))
        <p class="mb-4 text-sm text-red-600 bg-green-100 border border-red-300 rounded p-2">
            {{ session('error') }}
        </p>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 p-6">
        @foreach($cargos as $cargo)
            <div
                class="bg-gray-100 border border-{{$cargo->reservation_status === 'accepted' ? 'green' : 'blue'}}-300 rounded-lg shadow-md p-6 text-gray-800 relative">


                <div x-data="{ tab: 'info' }" class="mt-6">
                    <div class="flex border-b border-gray-300 mb-6 space-x-4 rtl:space-x-reverse">
                        <button
                            @click="tab = 'info'"
                            :class="tab === 'info' ? 'border-b-4 border-{{$cargo->reservation_status === 'accepted' ? 'green' : 'blue'}}-600 text-{{$cargo->reservation_status === 'accepted' ? 'green' : 'blue'}}-700 font-bold' : 'text-gray-500'"
                            class="flex-1 py-3 text-center transition"
                        >
                            اطلاعات بار
                        </button>

                        <button
                            @click="tab = 'status'"
                            :class="tab === 'status' ? 'border-b-4 border-{{$cargo->reservation_status === 'accepted' ? 'green' : 'blue'}}-600 text-{{$cargo->reservation_status === 'accepted' ? 'green' : 'blue'}}-700 font-bold' : 'text-gray-500'"
                            class="flex-1 py-3 text-center transition"
                        >
                            وضعیت
                        </button>
                    </div>

                    <div x-show="tab === 'info'" class="space-y-3 px-2 pb-3" x-cloak>
                        <h5 class="text-xl font-extrabold text-{{$cargo->reservation_status === 'accepted' ? 'green' : 'blue'}}-700">
                            بار: {{ $cargo->cargoType?->name ?? '---' }}</h5>
                        <p class="text-gray-600">مبدا: {{ $cargo->origin?->province?->name ?? '---' }}</p>
                        <p class="text-gray-600">مقصد: {{ $cargo->destination?->province?->name ?? '---' }}</p>

                        @if($cargo->reservation_status !== 'accepted')
                            <x-form.button
                                name="create"
                                action="{{ route('cargo_bids.list_of_bids' , $cargo->id) }}"
                                text="پیشنهادات "
                                type="button">
                                <i class="fa fa-legal text-2xl"></i>
                            </x-form.button>
                        @endif

                    </div>

                    <div x-show="tab === 'status'" class="space-y-3 px-2" x-cloak>
                        <p class="text-lg font-semibold text-{{$cargo->reservation_status === 'accepted' ? 'green' : 'blue'}}-700">وضعیت
                            کلی: {{ __('cargo_enums.reservation_status.' . $cargo->reservation_status )?? '---' }}</p>
                        @if($cargo->company?->name)
                            <p class="text-sm text-gray-700">
                                از شرکت حمل : {{$cargo->company->name ?? '-'}}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
