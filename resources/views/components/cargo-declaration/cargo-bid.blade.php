@php
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('cargo_bids.store')
        : ($isEdit ? route('cargo_bids.update', $cargoBid->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp

<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}} پیشنهاد
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('cargo_bids.update', $cargoBid->id) : route('cargo_bids.store')"
    :method="$mode === 'edit' ? 'PUT' : 'POST'"
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
    <div class="grid grid-cols gap-2">

        <div class="flex justify-center items-center min-h-[300px] p-6">
            <div
                class="max-w-md w-full bg-blue-100 border border-blue-500 rounded-xl shadow-lg p-6 text-center text-blue-900 font-bold transition-colors hover:bg-blue-200">
                <input type="hidden" name="cargo_id" value="{{ $cargoBid->cargo->id ?? $cargo?->id ?? '' }}">
                <h5 class="mb-4 text-2xl font-extrabold">
                    بار: {{ $cargoBid->cargo->cargoType?->name ?? $cargo?->cargoType?->name ?? '' }}</h5>
                <p class="text-lg mb-1">
                    مبدا: {{ $cargoBid->cargo->origin?->province?->name ?? $cargo?->origin?->province?->name ?? '' }} ,
                    مقصد:
                    {{ $cargoBid->cargo->destination?->province?->name ?? $cargo?->destination?->province?->name ?? '' }}</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols gap-2 border border-blue-300 p-5 rounded-xl">
        <div class="flex justify-center ">
            <x-form.input
                name="offered_fare"
                label="قیمت پیشنهادی"
                type="text"
                placeholder="قیمت پیشنهادی را وارد کنید"
                value="{{ old('offered_fare' , $cargoBid->offered_fare ?? '') }}"
                :numberFormat="true"
            />

        </div>

        <div class="">
            <x-form.input
                name="note"
                label="شرح پیشنهاد"
                type="textarea"
                placeholder="شرح پیشنهاد را وارد کنید"
                value="{{ old('note' , $cargoBid->note ?? '') }}"
            />

        </div>


    </div>

    <div>

        <x-form.button
            type="submit"
            text="{{ $mode === 'edit' ? 'ویرایش پیشنهاد' : 'ثبت پیشنهاد' }}"
            :mode="$mode"
        />
    </div>
</x-form.base-form>


