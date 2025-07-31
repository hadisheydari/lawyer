@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('vehicle_details.store')
        : ($isEdit ? route('vehicle_details.update', $vehicleDetail->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp
<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}}بارگیر
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('vehicle_details.update', $vehicleDetail->id) : route('vehicle_details.store')"
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


    </div>

    <div class="">
        <x-form.input
            name="brand"
            label="برند نوع بارگیر "
            type="text"
            placeholder="برند نوع بارگیر  را وارد کنید"
            value="{{ old('brand' , $vehicleDetail->brand ?? '') }}"
            :readonly="$isShow"

        />

    </div>
    <div class="">
        <x-form.input
            name="name"
            label="مدل نوع بارگیر "
            type="text"
            placeholder="مدل نوع بارگیر  را وارد کنید"
            value="{{ old('name' , $vehicleDetail->name ?? '') }}"
            :readonly="$isShow"

        />

    </div>
    <div class="">
        <x-form.input
            name="motorCode"
            label="شناسه موتور نوع بارگیر "
            type="text"
            placeholder="شناسه موتور نوع بارگیر  را وارد کنید"
            value="{{ old('motorCode' , $vehicleDetail->motorCode ?? '') }}"
            :readonly="$isShow"

        />

    </div>
    <div class="">
        <x-form.input
            name="bodyCode"
            label="شناسه بدنه نوع بارگیر "
            type="text"
            placeholder="شناسه بدنه نوع بارگیر  را وارد کنید"
            value="{{ old('bodyCode' , $vehicleDetail->bodyCode ?? '') }}"
            :readonly="$isShow"

        />

    </div>

    <div class="">
        <x-form.input
            name="year"
            label=" سال ساخت نوع بارگیر "
            type="number"
            placeholder="سال ساخت نوع بارگیر را وارد کنید"
            value="{{ old('year'  , $vehicleDetail->year ?? '' ) }}"
            min="1"
            max="4"
            :readonly="$isShow"
        />

    </div>



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


