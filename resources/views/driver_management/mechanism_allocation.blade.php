@extends('layouts.main')
@push('imports')
    @vite(['resources/js/methods/mechanism_allocation.js'])
@endpush
@section('title', 'ویرایش راننده ')
@section('content')
<div class="text-blue-950 font-black text-2xl m-12 ">
    تخصیص مکانیزم به راننده
</div>
<x-form.base-form
    id="assignForm"
    :action="''"
    :method="'PUT'"
    class="space-y-6">
    @csrf
    @if ($errors->any())
        <div class="rounded-md bg-red-50 p-4 text-red-700 text-sm font-medium space-y-1">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="grid grid-cols-3 gap-4">
        @php
            $previous = url()->previous();
            $isSelfRedirect = Str::contains($previous, route('drivers.edit', $driver));
            $redirectTo = $isSelfRedirect ? route('drivers.index') : $previous;
        @endphp

        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
        <input type="hidden" name="driver_id" value="{{ $driver->id }}">

        <div class="">
            <x-form.input
                name="national_code"
                label="کد ملی"
                type="text"
                placeholder="کد ملی را وارد کنید"
                value="{{ old('national_code'  , $driver->national_code ?? '' ) }}"
                :readonly="true"
            />

        </div>

        <div class="">
            <x-form.input
                name="name"
                label="نام"
                type="text"
                placeholder="نام را وارد کنید"
                value="{{ old('name' , $driver->user->name ?? '') }}"
                :readonly="true"

            />

        </div>

        <div class="">
            <x-form.input
                name="phone"
                label="تلفن"
                type="text"
                placeholder="تلفن را وارد کنید"
                value="{{ old('phone' , $driver->user->phone ?? '') }}"
                :readonly="true"

            />

        </div>

        <div class="">
            <x-form.select-box
                id="vehicleSelect"
                name="vehicles"
                :options="$vehicles ?? []"
                label="مکانیزم "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('vehicles', $driver->vehicle->id ?? '')"
                :multiple="false"
                :required="true"
            />
        </div>


    </div>


    <div>

            <x-form.button
                type="submit"
                text=" ویرایش اطلاعات "
                :mode="'edit'"
            />
    </div>
</x-form.base-form>

@endsection

