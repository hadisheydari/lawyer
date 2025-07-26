<div class="text-blue-950 font-black text-2xl m-12 ">
   ثبت اطلاعات شرکت حمل
</div>
<x-form.base-form action="{{route('companies.store')}}" method="POST" class="space-y-6">
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
                :multiple="false"
                :required="true"
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
            <x-form.input
                name="registration_id"
                label="شناسه ثبت"
                type="text"
                placeholder="شناسه ثبت را وارد کنید"
                value="{{ old('registration_id') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="national_id"
                label="شناسه ملی"
                type="text"
                placeholder="شناسه ملی را وارد کنید"
                value="{{ old('national_id') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="rahdari_code"
                label="کد راهداری"
                type="text"
                placeholder="کد راهداری را وارد کنید"
                value="{{ old('rahdari_code') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="agent_name"
                label="نام نماینده"
                type="text"
                placeholder="نام نماینده را وارد کنید"
                value="{{ old('agent_name') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="agent_national_code"
                label="کدملی مدیرعامل"
                type="text"
                placeholder="کدملی مدیرعامل را وارد کنید"
                value="{{ old('agent_national_code') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="agent_phone_number"
                label="شماره نماینده"
                type="text"
                placeholder="شماره نماینده را وارد کنید"
                value="{{ old('agent_phone_number') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_name"
                label="نام مدیرعامل"
                type="text"
                placeholder="نام مدیرعامل را وارد کنید"
                value="{{ old('manager_name') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_national_code"
                label="کدملی مدیرعامل"
                type="text"
                placeholder="کدملی مدیرعامل را وارد کنید"
                value="{{ old('manager_national_code') }}"
            />

        </div>

        <div class="">
            <x-form.input
                name="manager_phone_number"
                label="شماره مدیرعامل"
                type="text"
                placeholder="شماره مدیرعامل را وارد کنید"
                value="{{ old('manager_phone_number') }}"
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


