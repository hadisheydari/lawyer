@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('vehicles.store')
        : ($isEdit ? route('vehicles.update', $vehicle->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp

<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}}  مکانیزم
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('vehicles.update', $vehicle->id) : route('vehicles.store')"
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
                name="smart_number"
                label="شماره هوشمند"
                type="number"
                placeholder="شماره هوشمند را وارد کنید"
                value="{{ old('smart_number'  , $vehicle->smart_number ?? '' ) }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="cost_center"
                label="مرکز هزینه"
                type="number"
                placeholder="مرکز هزینه را وارد کنید"
                value="{{ old('cost_center' , $vehicle->cost_center ?? '') }}"
                :readonly="$isShow"

            />

        </div>

        <div class="">
            <x-form.select-box
                name="vehicle_detail_id"
                :options="$vehicleDetails ?? []"
                label="نوع مکانیزم"
                placeholder="یک گزینه را انتخاب کنید"
                :selected="old('vehicle_detail_id', $vehicle->vehicle_detail_id ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>

        <div class="">
            <x-form.select-box
                name="plate_type"
                :options="__('vehicle_enums.plate_types')"
                label="نوع پلاک "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('plate_type', $vehicle->plate_type ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>

        <div class="">
            <x-form.input
                name="plate_first"
                label="قسمت اول پلاک"
                type="number"
                placeholder="قسمت اول پلاک را وارد کنید"
                value="{{ old('plate_first'  , $vehicle->plate_first ?? '' ) }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.select-box
                name="plate_letter"
                :options="__('vehicle_enums.plate_letters')"
                label="حرف پلاک"
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('plate_letter', $vehicle->plate_letter ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>

        <div class="">
            <x-form.input
                name="plate_second"
                label="قسمت دوم پلاک"
                type="number"
                placeholder="قسمت دوم پلاک را وارد کنید"
                value="{{ old('plate_second'  , $vehicle->plate_second ?? '' ) }}"
                :readonly="$isShow"
            />

        </div>
        <div class="">
            <x-form.input
                name="plate_third"
                label="قسمت سوم پلاک(کد استان)"
                type="number"
                placeholder="قسمت سوم پلاک(کد استان) را وارد کنید"
                value="{{ old('plate_third'  , $vehicle->plate_third ?? '' ) }}"

                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.select-box
                name="status"
                :options="__('vehicle_enums.status')"
                label="وضعیت"
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('status', $vehicle->status ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>

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


