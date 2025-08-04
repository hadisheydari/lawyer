@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('cargos.store')
        : ($isEdit ? route('cargos.update', $cargo->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp
<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}} اطلاعات بار
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('cargos.update', $cargo->id) : route('cargos.store')"
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
                name="cargo_type_id"
                :options=" $cargoTypes ?? []"
                label="نوع بار "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('cargo_type_id', $cargo->cargo_type_id ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>

        <div class="">
            <x-form.select-box
                name="packing_id"
                :options="$packings ?? []"
                label="نوع بسته بندی "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('packing_id', $cargo->packing_id ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>

        <div class="">
            <x-form.input
                name="weight"
                label="وزن بار بر اساس (تن)"
                type="text"
                placeholder="وزن بار  را وارد کنید"
                value="{{ old('weight' , $cargo->weight ?? '') }}"
                :readonly="$isShow"
                :numberFormat="true"

            />

        </div>


        <div class="">
            <x-form.input
                name="number"
                label="تعداد بار"
                type="number"
                placeholder="تعداد بار را وارد کنید"
                value="{{ old('number'  , $cargo->number ?? '' ) }}"
                :readonly="$isShow"
            />

        </div>

        <div class="">
            <x-form.input
                name="thickness"
                label="ضخامت بار بر اساس (متر)"
                type="text"
                placeholder="ضخامت بار  را وارد کنید"
                value="{{ old('thickness' , $cargo->thickness ?? '') }}"
                :readonly="$isShow"
                :numberFormat="true"

            />

        </div>

        <div class="">
            <x-form.input
                name="length"
                label="طول بار بر اساس (متر)"
                type="text"
                placeholder="طول بار  را وارد کنید"
                value="{{ old('length' , $cargo->length ?? '') }}"
                :readonly="$isShow"
                :numberFormat="true"

            />

        </div>

        <div class="">
            <x-form.input
                name="width"
                label="عرض بار بر اساس (متر)"
                type="text"
                placeholder="عرض بار  را وارد کنید"
                value="{{ old('width' , $cargo->width ?? '') }}"
                :readonly="$isShow"
                :numberFormat="true"

            />

        </div>


        <div class="">
            <x-form.select-box
                name="insurance_id"
                :options="$insurances->pluck('name', 'id') ?? []"
                label="نوع بیمه"
                placeholder="یک گزینه را انتخاب کنید"
                :selected="old('insurance_id', $vehicle->insurance_id ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>


        <div class="">
            <x-form.input
                name="insurance_value"
                label="ارزش بیمه بر حسب (ریال)"
                type="text"
                placeholder="ارزش بیمه را وارد کنید"
                value="{{ old('insurance_value' , $cargo->insurance_value ?? '') }}"
                :readonly="$isShow"
                :numberFormat="true"

            />

        </div>

        <div class="">
            <x-form.input
                name="fare_value"
                label="مبلغ کرایه بر حسب (ریال)"
                type="text"
                placeholder="مبلغ کرایه را وارد کنید"
                value="{{ old('fare_value' , $cargo->fare_value ?? '') }}"
                :readonly="$isShow"
                :numberFormat="true"

            />

        </div>

        <div class="">
            <x-form.input
                name="fare"
                label="مبلغ کرایه محاسبه شده بر حسب (ریال)"
                type="text"
                placeholder="مبلغ کرایه را وارد کنید"
                value="{{ old('fare' , $cargo->fare ?? '') }}"
                :readonly="true"
                :numberFormat="true"

            />

        </div>

        <div class="">
            <x-form.select-box
                name="fare_type"
                :options="__('cargo_enums.fare_type')"
                label="مالکیت "
                placeholder="یک گزینه را انتخاب کنید "
                :selected="old('fare_type', $cargo->fare_type ?? '')"
                :multiple="false"
                :required="true"
                :disabled="$isShow"
            />
        </div>


    </div>
    <x-cargo_declaration.cargo-information
        :provinces="$provinces"
        :cities="$cities"
        :locationType="'origin'"
        :isShow="$isShow"
    />
    <x-cargo_declaration.cargo-information
        :provinces="$provinces"
        :cities="$cities"
        :locationType="'destination'"
        :isShow="$isShow"
    />
    <div class="">
        <x-form.input
            name="description"
            label="توضیحات"
            type="textarea"
            placeholder="توضیحات را وارد کنید"
            value="{{ old('description' , $company->description ?? '') }}"
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


