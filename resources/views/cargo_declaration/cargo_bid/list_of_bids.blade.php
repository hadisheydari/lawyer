@extends('layouts.main')

@section('title', 'لیست پیشنهادات بار ازاد ')
@section('header', 'لیست پیشنهادات بار ازاد ')

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


    @can('write bids')
        <div class="!m-6 w-full grid justify-start">
            <x-form.button
                name="create"
                type="button"
                text="افزودن پیشنهاد "
                :action="route('cargo_bids.create' , $cargo)"
            />
        </div>

    @endcan



    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 p-6">
        @foreach($cargo_bids as $cargo_bid)
            <div
                class="bg-gray-100 border border-{{$cargo_bid->status === 'rejected' ? 'red' : 'blue'}}-300 rounded-lg shadow-md p-6 text-gray-800 relative">


                <div x-data="{ tab: 'info' }" class="mt-6">
                    <div class="flex border-b border-gray-300 mb-6 space-x-4 rtl:space-x-reverse">
                        <button
                            @click="tab = 'info'"
                            :class="tab === 'info' ? 'border-b-4 border-{{$cargo_bid->status === 'rejected' ? 'red' : 'blue'}}-600 text-{{$cargo_bid->status === 'rejected' ? 'red' : 'blue'}}-700 font-bold' : 'text-gray-500'"
                            class="flex-1 py-3 text-center transition"
                        >
                            پیشنهاد
                        </button>

                        @can('accept bids')
                            @if($cargo_bid->status === 'pending')
                                <button
                                    @click="tab = 'accept'"
                                    :class="tab === 'accept' ? 'border-b-4 border-blue-600 text-blue-700 font-bold' : 'text-gray-500'"
                                    class="flex-1 py-3 text-center transition"
                                >
                                    تایید بار
                                </button>
                            @endif
                        @endcan
                    </div>

                    <div x-show="tab === 'info'" class="space-y-3 px-2" x-cloak>
                        <h5 class="text-xl font-extrabold text-{{$cargo_bid->status === 'rejected' ? 'red' : 'blue'}}-700">
                            شرکت : {{ $cargo_bid->company?->name ?? '---' }}</h5>
                        <hr>
                        <p class="text-gray-600">
                            قیمت: {{ $cargo_bid->offered_fare ? number_format($cargo_bid->offered_fare) : '---' }}
                        </p>
                        <hr>
                        <p class="text-gray-600">شرح پیشنهاد: {{ $cargo_bid->note ?? '---' }}</p>



                    </div>

                    <div x-show="tab === 'accept'" class="space-y-3 px-2" x-cloak>
                        <div class="grid grid-cols-2 gap-2">
                            <a
                                onclick="SweetAlert(this, null, null, 'بله تایید پیشنهاد', null, '{{ route('cargo_reservations.confirmCargo', ['cargo' => $cargo, 'status' => 'accepted']) }}')"
                                href="#"
                                class="inline-block w-full text-center py-2 px-4 rounded bg-green-500 text-white hover:bg-green-600 transition"
                            >
                                تایید پیشنهاد
                            </a>

                            <a
                                onclick="SweetAlert(this, null, null, 'بله رد پیشنهاد', null, '{{ route('cargo_reservations.confirmCargo', ['cargo' => $cargo, 'status' => 'rejected']) }}')"
                                href="#"
                                class="inline-block w-full text-center py-2 px-4 rounded bg-red-500 text-white hover:bg-red-600 transition"
                            >
                                رد پیشنهاد
                            </a>


                        </div>
                    </div>
                </div>
                @endforeach
            </div>

@endsection
