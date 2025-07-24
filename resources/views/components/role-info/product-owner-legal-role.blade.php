<div class="text-blue-950 font-black text-2xl m-12 ">
    ثبت اطلاعات صاحب کالا(حقیقی)
</div>
<x-form.base-form action="{{route('product-owners.store')}}" method="POST" class="space-y-6">
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
                name="city_id"
                :options="$cities ?? []"
                label="شهر "
                placeholder="یک گزینه را انتخاب کنید "
                :multiple="false"
                :required="true"
            />
        </div>

        <div class="">
            <x-form.input
                name="national_code"
                label="کد ملی"
                type="text"
                placeholder="کد ملی را وارد کنید"

                value="{{ old('national_code') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="bank_name"
                label="نام بانک"
                type="text"
                placeholder="نام بانک را وارد کنید"
                value="{{ old('bank_name') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="sheba_number"
                label="شماره شبا"
                type="text"
                placeholder="شماره شبا را وارد کنید"
                value="{{ old('sheba_number') }}"
            />

        </div>


        <div class="">
            <x-form.input
                name="address"
                label="آدرس"
                type="textarea"
                placeholder="آدرس را وارد کنید"
                value="{{ old('address') }}"
            />

        </div>
        <div class="">
            <x-form.image
                name="document"
                label="مدارک"
            />

        </div>


    </div>
    {{ $slot }}

    <div>
        <x-form.button text="ثبت اطلاعات" type="submit" class="w-full"/>
    </div>
</x-form.base-form>


