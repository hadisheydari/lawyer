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
    {{$translate[$mode]}}  اطلاعات صاحب کالا(حقیقی)
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

                value="{{ old('national_code', $productOwner->national_code ?? '') }}"
            />

        </div>


        <div class="">
            <x-form.input
                name="registration_id"
                label="شناسه ثبت"
                type="text"
                placeholder="شناسه ثبت را وارد کنید"
                value="{{ old('registration_id', $productOwner->registration_id ?? '') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="national_id"
                label="شناسه ملی"
                type="text"
                placeholder="شناسه ملی را وارد کنید"
                value="{{ old('national_id', $productOwner->national_id ?? '') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="rahdari_code"
                label="کد راهداری"
                type="text"
                placeholder="کد راهداری را وارد کنید"
                value="{{ old('rahdari_code' , $productOwner->rahdari_code ?? '') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="agent_name"
                label="نام نماینده"
                type="text"
                placeholder="نام نماینده را وارد کنید"
                value="{{ old('agent_name' , $productOwner->agent_name ?? '') }}"
            />

        </div>
        <div class="">
            <x-form.input
                name="agent_national_code"
                label="کدملی نماینده"
                type="text"
                placeholder="کدملی نماینده را وارد کنید"
                value="{{ old('agent_national_code' , $productOwner->agent_national_code ?? '') }}"
            />

        </div>


        <div class="">
            <x-form.input
                name="agent_phone_number"
                label="شماره نماینده"
                type="text"
                placeholder="شماره نماینده را وارد کنید"
                value="{{ old('agent_phone_number' , $productOwner->agent_phone_number ?? '') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_name"
                label="نام مدیرعامل"
                type="text"
                placeholder="نام مدیرعامل را وارد کنید"
                value="{{ old('manager_name' , $productOwner->manager_name ?? '') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_national_code"
                label="کدملی مدیرعامل"
                type="text"
                placeholder="کدملی مدیرعامل را وارد کنید"
                value="{{ old('manager_national_code' , $productOwner->manager_national_code ?? '') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_phone_number"
                label="شماره مدیرعامل"
                type="text"
                placeholder="شماره مدیرعامل را وارد کنید"
                value="{{ old('manager_phone_number' , $productOwner->manager_phone_number ?? '') }}"
            />

        </div>



        <div class="">
            <x-form.input
                name="address"
                label="آدرس"
                type="textarea"
                placeholder="آدرس را وارد کنید"
                value="{{ old('address' , $productOwner->address ?? '') }}"
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


