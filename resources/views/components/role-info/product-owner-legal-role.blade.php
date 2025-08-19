@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('product-owners.store')
        : ($isEdit ? route('product-owners.update', $productOwner->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp

<div class="text-blue-950 font-black text-2xl m-12 ">
    {{ config('app.market_name') }}  {{$translate[$mode]}}  اطلاعات صاحب کالا(حقوقی)
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('product-owners.update', $productOwner->id) : route('product-owners.store')"
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
    <div class="grid grid-cols-3 gap-4">

        <div class="">
            <x-form.select-box
                name="province_id"
                :options="$provinces ?? []"
                label="استان"
                placeholder="یک گزینه را انتخاب کنید"
                :selected="old('province_id', $productOwner->province_id ?? '')"
                :multiple="false"
                :required="true"
                id="province"
                :disabled="$isShow"

            />
        </div>

        <div class="">
            <x-form.select-box
                name="city_id"
                :options="$cities ?? []"
                label="شهر"
                placeholder="ابتدا استان را انتخاب کنید"
                :multiple="false"
                :required="true"
                :selected="old('province_id', $productOwner->city_id ?? '')"
                id="city"
                :disabled="$isShow"

            />
        </div>


        <div class="">
            <x-form.input
                name="national_code"
                label="کد ملی"
                type="text"
                placeholder="کد ملی را وارد کنید"
                :readonly="$isShow"
                value="{{ old('national_code', $productOwner->national_code ?? '') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="bank_name"
                label="نام بانک"
                type="text"
                placeholder="نام بانک را وارد کنید"
                value="{{ old('bank_name', $productOwner->bank_name ?? '') }}"
                :readonly="$isShow"

            />

        </div>

        <div class="">
            <x-form.input
                name="sheba_number"
                label=" شماره شبا( بدون IR  )"
                type="text"
                placeholder="شماره شبا را وارد کنید"
                value="{{ old('sheba_number', $productOwner->sheba_number ?? '') }}"
                :readonly="$isShow"

            />

        </div>


        <div class="">
            <x-form.input
                name="address"
                label="آدرس"
                type="textarea"
                placeholder="آدرس را وارد کنید"
                value="{{ old('address' , $productOwner->address ?? '') }}"
                :readonly="$isShow"

            />

        </div>
        <div class="">
            <x-form.image
                name="document"
                label="مدارک"
                :currentImage="$productOwner->document ?? null"
                :readonly="$mode === 'show'"
                :required="$mode === 'create'"
            />

        </div>


    </div>
    {{ $slot }}

    <div>
        @if($mode === 'show')
            <x-form.button
                type="button"
                text="بازگشت"
                :mode="$mode"
                :action="'window.location.href=\''.route('product-owners.index').'\''"
            />
        @else
            <x-form.button
                type="submit"
                text="{{ $mode === 'edit' ? 'ویرایش اطلاعات' : 'ثبت اطلاعات' }}"
                :mode="$mode"
            />
        @endif
    </div>
</x-form.base-form>


