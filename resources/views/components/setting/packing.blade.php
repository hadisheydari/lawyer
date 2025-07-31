@php
    $isShow = $mode === 'show';
    $isEdit = $mode === 'edit';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('packings.store')
        : ($isEdit ? route('packings.update', $packing->id) : '#');

    $method = $isCreate ? 'POST' : ($isEdit ? 'PUT' : 'GET');

    $translate=[
        'show' => 'نمایش',
        'edit' => 'ویرایش',
        'create' => 'ثبت',

];
@endphp
<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}}  اطلاعات نوع بسته بندی
</div>
<x-form.base-form
    :action="$mode === 'edit' ? route('packings.update', $packing->id) : route('packings.store')"
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
            name="name"
            label="نام نوع بسته بندی"
            type="text"
            placeholder="نام نوع بسته بندی  را وارد کنید"
            value="{{ old('name' , $packing->name ?? '') }}"
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


