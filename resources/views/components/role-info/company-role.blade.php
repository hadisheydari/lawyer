@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('companies.store')
        : ($isEdit ? route('companies.update', $company->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp

<div class="text-blue-950 font-black text-2xl m-12 ">
           {{$translate[$mode]}}  اطلاعات شرکت حمل
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('companies.update', $company->id) : route('companies.store')"
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
                name="company_type"
                :options="['normal' => 'معمولی', 'large_scale' => 'بزرگ مقیاس ']"
                label="نوع شرکت "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('company_type', $company->company_type ?? '')"
                :multiple="false"
                :disabled="$isShow"

            />
        </div>
        <div class="">
            <x-form.select-box
                name="province_id"
                :options="$provinces ?? []"
                label="استان"
                placeholder="یک گزینه را انتخاب کنید"
                :selected="old('province_id', $company->province_id ?? '')"
                :multiple="false"
                :disabled="$isShow"
                id="province"
            />
        </div>

        <div class="">
            <x-form.select-box
                name="city_id"
                :options="$cities ?? []"
                label="شهر"
                placeholder="ابتدا استان را انتخاب کنید"
                :selected="old('city_id', $company->city_id ?? '')"
                :multiple="false"
                :disabled="$isShow"
                id="city"
            />
        </div>

        <div class="">
            <x-form.input
                name="registration_id"
                label="شناسه ثبت"
                type="text"
                placeholder="شناسه ثبت را وارد کنید"
                value="{{ old('registration_id' , $company->registration_id ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="national_id"
                label="شناسه ملی"
                type="text"
                placeholder="شناسه ملی را وارد کنید"
                value="{{ old('national_id', $company->national_id ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="rahdari_code"
                label="کد راهداری"
                type="text"
                placeholder="کد راهداری را وارد کنید"
                value="{{ old('rahdari_code', $company->rahdari_code ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="agent_name"
                label="نام نماینده"
                type="text"
                placeholder="نام نماینده را وارد کنید"
                value="{{ old('agent_name', $company->agent_name ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="agent_national_code"
                label="کدملی مدیرعامل"
                type="text"
                placeholder="کدملی مدیرعامل را وارد کنید"
                value="{{ old('agent_national_code', $company->agent_national_code ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="agent_phone_number"
                label="شماره نماینده"
                type="text"
                placeholder="شماره نماینده را وارد کنید"
                value="{{ old('agent_phone_number', $company->agent_phone_number ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_name"
                label="نام مدیرعامل"
                type="text"
                placeholder="نام مدیرعامل را وارد کنید"
                value="{{ old('manager_name', $company->manager_name ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_national_code"
                label="کدملی مدیرعامل"
                type="text"
                placeholder="کدملی مدیرعامل را وارد کنید"
                value="{{ old('manager_national_code' , $company->manager_national_code ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_phone_number"
                label="شماره مدیرعامل"
                type="text"
                placeholder="شماره مدیرعامل را وارد کنید"
                value="{{ old('manager_phone_number' , $company->manager_phone_number ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="address"
                label="آدرس"
                type="textarea"
                placeholder="آدرس را وارد کنید"
                value="{{ old('address' , $company->address ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.image
                name="document"
                label="مدارک"
                :currentImage="$company->document ?? null"
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
                :action="'window.location.href=\''.route('companies.index').'\''"
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


