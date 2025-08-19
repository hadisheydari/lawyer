@extends('layouts.main')

@section('title', 'امتیاز برای راننده')

@section('content')


        <x-form.base-form
            id="assignForm"
            :action="''"
            :method="'PUT'"
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

            <!-- ستاره‌ها با label و glow -->
            <div x-data="{ rating: 0, ratingText: '' }" class="flex flex-col items-center mb-6 space-y-2 rtl:space-x-reverse">
                <div class="flex space-x-2 rtl:space-x-reverse">
                    <template x-for="i in 5" :key="i">
                        <button type="button" @mouseover="ratingText = ['خیلی بد','بد','خوب','خیلی خوب','عالی'][i-1]" @mouseleave="ratingText = ''" @click="rating = i; ratingText = ['خیلی بد','بد','خوب','خیلی خوب','عالی'][i-1]" class="focus:outline-none relative">
                            <svg
                                :class="i <= rating ? 'text-yellow-400 drop-shadow-lg' : 'text-gray-300'"
                                class="w-32 h-32 transition-colors duration-300 transform hover:scale-110"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <polygon points="10 1.5 12.59 7.36 18.9 7.36 13.66 11.63 15.66 17.5 10 13.36 4.34 17.5 6.34 11.63 1.1 7.36 7.41 7.36 10 1.5"/>
                            </svg>
                        </button>
                    </template>
                </div>

                <!-- متن امتیاز -->
                <div class="text-gray-700 font-semibold text-lg min-h-[1.5rem]" x-text="ratingText"></div>

                <input type="hidden" name="rating" :value="rating">
            </div>



            <div>
{{--                @if($mode === 'show')--}}
{{--                    <x-form.button--}}
{{--                        type="button"--}}
{{--                        text="بازگشت"--}}
{{--                        :mode="$mode"--}}
{{--                        :action="'window.history.back()'"--}}
{{--                    />--}}
{{--                @else--}}
                    <x-form.button
                        type="submit"
                        text="ثبت امتیاز"
                        :mode="'create'"
                    />
{{--                @endif--}}
            </div>
        </x-form.base-form>

@endsection
@section('script')
    <script src="//unpkg.com/alpinejs" defer></script>

@endsection
