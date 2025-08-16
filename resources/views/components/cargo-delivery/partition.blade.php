@php
    // تعیین حالت صفحه
    $isShow   = $mode === 'show';
    $isEdit   = $mode === 'edit';
    $isCreate = $mode === 'create';
    $isStatus = isset($status) && $status === 'upload';

    $action = $isCreate
        ? route('partitions.store')
        : ($isEdit ? route('partitions.update', $partition->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $statusFree = $partition->status === 'free';

    $translate = [
        'edit'   => $statusFree
                        ? ' ویرایش پارتیشن '
                        : ' ویرایش ' . __('cargo_enums.cargo_status.' . $partition->status),
        'show'   => 'نمایش پارتیشن ',
        'upload' => 'آپلود ' . __('cargo_enums.partition_upload.' . $partition->status),
        'create' => 'ثبت پارتیشن ',
    ];

    $titleKey = $status ?? $mode;
    $title    = $translate[$titleKey] ?? 'مدیریت پارتیشن';

    $showBarnameh =
        ($partition->status === 'havale' && $isStatus) ||
        ($partition->status === 'barnameh' && !$isStatus) ||
        $partition->status === 'delivered';
@endphp


<div class="text-blue-950 font-black text-2xl m-12 ">
    {{ $title }}
</div>

<x-form.base-form
    :action="$action"
    :method="$method"
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


    <div class="grid grid-cols-2 gap-4">
        <input type="hidden" name="cargo_id" value="{{ $partition->cargo_id ?? $cargo->id ?? '' }}">

        {{-- وزن --}}
        <x-form.input
            name="weight"
            label="وزن پارتیشن بر اساس (تن)"
            type="text"
            placeholder="وزن بار را وارد کنید"
            value="{{ old('weight', $partition->weight ?? '') }}"
            :readonly="!($isCreate || $statusFree)"
            :numberFormat="true"
        />

        <x-form.select-box
            name="vehicle_detail_id"
            :options="$vehicleDetails ?? []"
            label="نوع مکانیزم"
            placeholder="یک گزینه را انتخاب کنید"
            :selected="old('vehicle_detail_id', $partition->vehicle_detail_id ?? '')"
            :multiple="false"
            :required="true"
            :disabled="!($isCreate || $statusFree)"
        />

        <x-form.input
            id="fare"
            name="fare"
            label="کرایه پارتیشن"
            type="text"
            placeholder="کرایه پارتیشن را وارد کنید"
            value="{{ old('fare', $partition->fare ?? '') }}"
            :readonly="true"
            :numberFormat="true"
        />

        <x-form.input
            name="commission"
            label="کمیسیون پارتیشن"
            type="text"
            placeholder="کمیسیون پارتیشن را وارد کنید"
            value="{{ old('commission', $partition->commission ?? '') }}"
            :readonly="!($isCreate || $statusFree)"
            :numberFormat="true"
        />
    </div>


    @if(!($isCreate ||$statusFree ))
        <div class="grid grid-cols-{{ $showBarnameh ? '2' : '1' }} gap-4 mt-5">
            {{-- حواله --}}
            <x-form.image
                name="havaleFile"
                label="فایل حواله نامه"
                :currentImage="$partition->havaleFile ?? null"
                :required="$mode === 'create'"
                :readonly="($isShow || $partition->status === 'barnameh' || ($partition->status === 'havale' && $isStatus))"
            />

            {{-- بارنامه --}}
            <div class="{{ $showBarnameh ? '' : 'hidden' }}">
                <x-form.image
                    name="barnamehFile"
                    label="فایل بارنامه"
                    :currentImage="$partition->barnamehFile ?? null"
                    :required="$mode === 'create'"
                    :readonly="($isShow || $partition->status === 'delivered')"
                />
            </div>
        </div>
    @endif


    <div>
        @if ($isShow)
            <x-form.button
                type="button"
                text="بازگشت"
                :mode="$mode"
                :action="'window.history.back()'"
            />
        @else
            <x-form.button
                type="submit"
                text="{{ $isEdit ? 'ویرایش اطلاعات' : 'ثبت اطلاعات' }}"
                :mode="$mode"
            />
        @endif
    </div>
</x-form.base-form>
