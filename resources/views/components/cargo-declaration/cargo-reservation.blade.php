@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $isMultiple = $cargo->type === 'rfq'  ;
    $action = $isCreate
        ? route('cargo_reservations.store')
        : ($isEdit ? route('cargo_reservations.update', $cargo->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp
<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}}  {{ $cargo->type === 'rfq' ? 'rfq' : 'رزرو'}} بار
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('cargo_reservations.update', $cargo->id) : route('cargo_reservations.store')"
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
    <div class="grid grid-cols  gap-4">
        <div class="flex justify-center items-center min-h-[300px] p-6">
            <div class="max-w-md w-full bg-blue-100 border border-blue-500 rounded-xl shadow-lg p-6 text-center text-blue-900 font-bold transition-colors hover:bg-blue-200">
                <input type="hidden" name="redirect_to" value="{{ $cargo->type ?? '' }}">
                <input type="hidden" name="cargo_id" value="{{ $cargo->id ?? '' }}">
                <h5 class="mb-4 text-2xl font-extrabold">بار: {{ $cargo->cargoType?->name }}</h5>
                <p class="text-lg mb-1">مبدا: {{ $cargo->origin?->province?->name }} , مقصد: {{ $cargo->destination?->province?->name }}</p>
            </div>
        </div>

        <div class="">
                <x-form.select-box
                    name="company_id"
                    :options="$companies ?? []"
                    label="شرکت حمل"
                    placeholder="یک گزینه را انتخاب کنید"
                    :selected="old('company_id', $isMultiple ? $company : ($company ?? ''))"
                    :multiple="$isMultiple"
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


