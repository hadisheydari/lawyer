<div class="text-blue-950 font-black text-2xl m-12 ">
    ثبت اطلاعات صاحب کالا(حقیقی)
</div>
<x-form.base-form action="{{route('drivers.store')}}" method="POST" class="space-y-6">
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
                value="{{ old('national_code') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="birth_date"
                label="تاریخ تولد"
                type="date"
                placeholder="تاریخ تولد را وارد کنید"
                value="{{ old('birth_date') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="father_name"
                label="نام پدر"
                type="text"
                placeholder="نام پدر را وارد کنید"
                value="{{ old('father_name') }}"
            />

        </div>

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
            <x-form.select-box
                name="property"
                :options="['owned' => 'ملکی', 'non_owned' => 'غیرملکی ']"
                label="مالکیت "
                placeholder="یک گزینه را انتخاب کنید "
                :multiple="false"
                :required="true"
            />
        </div>

        <div class="hidden" id="company-field" >
            <x-form.select-box
                name="company_id"
                :options="$companies ?? []"
                label=" شرکت حمل "
                placeholder="یک گزینه را انتخاب کنید "
                :multiple="false"
                :required="true"
            />
        </div>

        <div class="">
            <x-form.input
                name="certificate_number"
                label="شماره گواهینامه"
                type="text"
                placeholder="شماره گواهینامه را وارد کنید"
                value="{{ old('certificate_number') }}"
            />

        </div>
        <div class="">
            <x-form.image
                name="national_card_file"
                label="فایل کارت ملی"
            />

        </div>

        <div class="">
            <x-form.image
                name="smart_card_file"
                label="فایل کارت هوشمند"
            />

        </div>

        <div class="">
            <x-form.image
                name="certificate_file"
                label="فایل گواهینامه"
            />

        </div>

    </div>
    {{ $slot }}

    <div>
        <x-form.button text="ثبت اطلاعات" type="submit" class="w-full"/>
    </div>
</x-form.base-form>


