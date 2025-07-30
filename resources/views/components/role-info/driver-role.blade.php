@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('drivers.store')
        : ($isEdit ? route('drivers.update', $driver->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp

<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}}  اطلاعات راننده
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('drivers.update', $driver->id) : route('drivers.store')"
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
            <x-form.input
                name="national_code"
                label="کد ملی"
                type="text"
                placeholder="کد ملی را وارد کنید"
                value="{{ old('national_code'  , $driver->national_code ?? '' ) }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="birth_date"
                label="تاریخ تولد"
                type="date"
                placeholder="تاریخ تولد را وارد کنید"
                value="{{ old('birth_date' , $driver->birth_date ?? '') }}"
                :readonly="$isShow"

            />

        </div>

        <div class="">
            <x-form.input
                name="father_name"
                label="نام پدر"
                type="text"
                placeholder="نام پدر را وارد کنید"
                value="{{ old('father_name' , $driver->father_name ?? '') }}"
                :readonly="$isShow"

            />

        </div>

        <div class="">
            <x-form.select-box
                name="province_id"
                :options="$provinces ?? []"
                label="استان"
                placeholder="یک گزینه را انتخاب کنید"
                :selected="old('province_id', $driver->province_id ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
                id="province"
            />
        </div>

        <div class="">
            <x-form.select-box
                name="city_id"
                :options="$cities ??[]"
                label="شهر"
                placeholder="ابتدا استان را انتخاب کنید"
                :selected="old('city_id', $driver->city_id ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
                id="city"
            />
        </div>

        <div class="">
            <x-form.select-box
                name="property"
                :options="['owned' => 'ملکی', 'non_owned' => 'غیرملکی ']"
                label="مالکیت "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('property', $driver->property ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>

        <div class=" {{$driver->property === 'owned' ? '' : 'hidden' }}" id="company-field" >
            <x-form.select-box
                name="company_id"
                :options="$companies ?? []"
                label=" شرکت حمل "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('property', $driver->company_id ?? '')"
                :multiple="false"
                :required="false"
                :disabled="$isShow"

            />
        </div>

        <div class="">
            <x-form.input
                name="certificate_number"
                label="شماره گواهینامه"
                type="text"
                placeholder="شماره گواهینامه را وارد کنید"
                value="{{ old('certificate_number' , $driver->certificate_number ?? '') }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.image
                name="national_card_file"
                label="فایل کارت ملی"
                :currentImage="$driver->national_card_file ?? null"
                :readonly="$mode === 'show'"
                :required="$mode === 'create'"
                :readonly="$isShow"

            />

        </div>

        <div class="">
            <x-form.image
                name="smart_card_file"
                label="فایل کارت هوشمند"
                :currentImage="$driver->smart_card_file ?? null"
                :readonly="$mode === 'show'"
                :required="$mode === 'create'"
            />

        </div>

        <div class="">
            <x-form.image
                name="certificate_file"
                label="فایل گواهینامه"
                :currentImage="$driver->certificate_file ?? null"
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
                :action="'window.history.back()'"
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


