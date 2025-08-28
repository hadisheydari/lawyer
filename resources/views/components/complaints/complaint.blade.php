@php
    $isShow = $mode === 'show';
    $isReview = $mode === 'review';
    $isCreate = $mode === 'create';

    $action = $isCreate
        ? route('complaints.store')
        : ($isReview ? route('complaints.review') : '#');


    $translate=[
        'show' => 'نمایش',
        'review' => 'بررسی',
        'create' => 'ثبت',

];
@endphp
<div class="text-blue-950 font-black text-2xl m-12 ">
    {{$translate[$mode]}}شکایت
</div>


@if ($errors->any())
    <div class="rounded-md bg-red-50 p-4 text-red-700 text-sm font-medium space-y-1">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="relative w-full h-full flex flex-col !rounded ">

    <div class="flex flex-col h-full bg-white !rounded-xl shadow-lg">

        <!-- هدر چت -->
        <div class="bg-blue-600 text-white text-center font-bold py-3 rounded-t-xl">
            چت با بات
        </div>

        <!-- پیام‌ها -->
        <div id="chatMessages" class="flex-1 flex flex-col gap-3 overflow-y-auto p-4" style="direction: ltr;">

            <!-- پیام بات سمت راست -->
            <div
                style="align-self: flex-end; background-color: #4B5563; color: white; border-radius: 0.75rem; padding: 0.75rem; width: 40%; word-break: break-word;">
                <div style="font-weight: 600; margin-bottom: 0.25rem;">بات:</div>
                <div>سلام! چگونه می‌توانم کمکتان کنم؟</div>
            </div>

            <!-- پیام کاربر سمت چپ -->
            <div
                style="align-self: flex-start; background-color: #2563EB; color: white; border-radius: 0.75rem; padding: 0.75rem; width: 40%; word-break: break-word;">
                <div style="font-weight: 600; margin-bottom: 0.25rem;">شما:</div>
                <div>سلام! می‌خواهم اطلاعاتی دریافت کنم.</div>
            </div>

        </div>
        <x-form.base-form
            :action="$isReview ? route('complaints.review') : route('complaints.store')"
            :method=" 'POST'"
            class="space-y-6">
            @csrf
            <!-- ورودی پیام -->
            @if($isCreate || $isShow)
                <div class="flex p-3 border-t border-gray-300 bg-white">

                    <div class="">
                        <x-form.input
                            name="title"
                            label="عنوان شکایت"
                            type="text"
                            placeholder="عنوان شکایت  را وارد کنید"
                            value="{{ old('title' , $complaint->title ?? '') }}"
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
                </div>
            @endif

            <div class="flex p-3 border-t border-gray-300 bg-white">

                <div class="">
                    <x-form.input
                        name="{{$isReview ? 'message' : 'description' }}"
                        label="توضیحات"
                        type="textarea"
                        placeholder="توضیحات را وارد کنید"
                        value="{{ old('description' , $complaint->description ?? '') }}"
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
                            text="{{ $isReview ? 'ثبت بررسی' : 'ثبت شکایت' }}"
                            :mode="$mode"
                        />
                    @endif
                </div>
            </div>
        </x-form.base-form>

    </div>

</div>




