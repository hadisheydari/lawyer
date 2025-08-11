@extends('layouts.main')
@php
    $color = [
        'accepted' => 'green',
        'rejected' => 'red',
        'pending' => 'gray',
    ];
@endphp
@section('title')
    لیست {{ $cargo === 'rfq' ? 'rfq' : 'رزرو' }} بار
@endsection
@section('header')
    لیست {{ $cargo === 'rfq' ? 'rfq' : 'رزرو' }} بار
@endsection


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
                class="bg-gray-100 border border-{{ $color[$cargo->reservation_status] }}-300 rounded-lg shadow-md p-6 text-gray-800 relative">

                <div class="absolute top-4 right-4" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="p-2 rounded-full hover:bg-{{ $color[$cargo->reservation_status] }}-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6 text-{{ $color[$cargo->reservation_status] }}-600" fill="currentColor"
                             viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="2"/>
                            <circle cx="12" cy="12" r="2"/>
                            <circle cx="19" cy="12" r="2"/>
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-2 w-44 bg-white border border-{{ $color[$cargo->reservation_status] }}-300 rounded-md shadow-lg z-10 text-gray-700"
                        style="display: none;"
                    >
                        <a href="{{ route('cargos.show' , $cargo->id) }}"
                           class="block px-4 py-2 hover:bg-{{ $color[$cargo->reservation_status] }}-100 transition">نمایش
                            بار</a>
                        @cannot('accept Reserve')
                            <a href="{{ route('cargo_reservations.edit' , $cargo->id) }}"
                               class="block px-4 py-2 hover:bg-{{ $color[$cargo->reservation_status] }}-100 transition">ویرایش</a>
                            <form action="{{ route('cargo_reservations.destroy' , $cargo->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        onclick="confirmDelete(this)"
                                        class="block px-4 py-2 hover:bg-{{ $color[$cargo->reservation_status] }}-100 transition">
                                    حذف
                                </button>
                            </form>
                        @endcan


                    </div>
                </div>

                <div x-data="{ tab: 'info' }" class="mt-6">
                    <div class="flex border-b border-gray-300 mb-6 space-x-4 rtl:space-x-reverse">
                        <button
                            @click="tab = 'info'"
                            :class="tab === 'info' ? 'border-b-4 border-{{ $color[$cargo->reservation_status] }}-600 text-{{ $color[$cargo->reservation_status] }}-700 font-bold' : 'text-gray-500'"
                            class="flex-1 py-3 text-center transition"
                        >
                            اطلاعات بار
                        </button>

                        @can('accept Reserve')
                            @if($cargo->reservation_status === 'pending')
                                <button
                                    @click="tab = 'accept'"
                                    :class="tab === 'accept' ? 'border-b-4 border-{{ $color[$cargo->reservation_status] }}-600 text-{{ $color[$cargo->reservation_status] }}-700 font-bold' : 'text-gray-500'"
                                    class="flex-1 py-3 text-center transition"
                                >
                                    تایید بار
                                </button>
                            @endif
                        @endcan
                        @cannot('accept Reserve')
                            <button
                                @click="tab = 'companies'"
                                :class="tab === 'companies' ? 'border-b-4 border-{{ $color[$cargo->reservation_status] }}-600 text-{{ $color[$cargo->reservation_status] }}-700 font-bold' : 'text-gray-500'"
                                class="flex-1 py-3 text-center transition"
                            >
                                شرکت حمل
                            </button>
                        @endcannot

                        <button
                            @click="tab = 'status'"
                            :class="tab === 'status' ? 'border-b-4 border-{{ $color[$cargo->reservation_status] }}-600 text-{{ $color[$cargo->reservation_status] }}-700 font-bold' : 'text-gray-500'"
                            class="flex-1 py-3 text-center transition"
                        >
                            وضعیت
                        </button>
                    </div>

                    <div x-show="tab === 'info'" class="space-y-3 px-2" x-cloak>
                        <h5 class="text-xl font-extrabold text-{{ $color[$cargo->reservation_status] }}-700">
                            بار: {{ $cargo->cargoType?->name ?? '---' }}</h5>
                        <p class="text-gray-600">مبدا: {{ $cargo->origin?->province?->name ?? '---' }}</p>
                        <p class="text-gray-600">مقصد: {{ $cargo->destination?->province?->name ?? '---' }}</p>
                    </div>

                    <div x-show="tab === 'companies'" class="space-y-3 px-2" x-cloak>
                        @forelse($cargo->reservations as $relation)
                            <div
                                class="bg-{{ $color[$cargo->reservation_status] }}-50 border border-{{ $color[$cargo->reservation_status] }}-200 rounded-md p-3 shadow-sm">
                                <p class="font-semibold text-{{ $color[$cargo->reservation_status] }}-700">شرکت
                                    حمل: {{ $relation->company->name ?? '---' }}</p>
                                <p class="text-sm text-gray-700">
                                    وضعیت: {{ __('cargo_enums.reservation_status.' . $relation->status) ?? $relation->status }}</p>

                            </div>
                        @empty
                            <p class="text-gray-500">هیچ شرکت حملی ثبت نشده است.</p>
                        @endforelse

                    </div>

                    <div x-show="tab === 'accept'" class="space-y-3 px-2" x-cloak>
                        <div class="grid grid-cols-2 gap-2">
                            <a
                                onclick="SweetAlert(this, null, null, 'بله تایید بار', null, '{{ route('cargo_reservations.confirmCargo', ['cargo' => $cargo->id, 'status' => 'accepted']) }}')"
                                href="#"
                                class="inline-block w-full text-center py-2 px-4 rounded bg-green-500 text-white hover:bg-green-600 transition"
                            >
                                تایید بار
                            </a>

                            <a
                                onclick="SweetAlert(this, null, null, 'بله رد بار', null, '{{ route('cargo_reservations.confirmCargo', ['cargo' => $cargo->id, 'status' => 'rejected']) }}')"
                                href="#"
                                class="inline-block w-full text-center py-2 px-4 rounded bg-red-500 text-white hover:bg-red-600 transition"
                            >
                                رد بار
                            </a>


                        </div>

                    </div>


                    <div x-show="tab === 'status'" class="space-y-3 px-2" x-cloak>
                        <p class="text-lg font-semibold text-{{ $color[$cargo->reservation_status] }}-700">وضعیت
                            کلی: {{ __('cargo_enums.reservation_status.' . $cargo->reservation_status )?? '---' }}</p>
                        @if($cargo->company?->name)
                            <p class="text-sm text-gray-700">
                                توسط: {{$cargo->company->name ?? '-'}}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
