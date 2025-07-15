@extends('layout.main')
@section('title', 'داشبورد مدیریتی ')
@section('header', 'داشبورد مدیریتی ')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
        <div class="bg-white rounded-2xl shadow-lg p-4 transition hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium">ثبت بار امروز</h3>
                <span class="text-green-500 text-xl">⬆</span>
            </div>
            <div class="text-2xl font-bold mb-1 mt-1">۱۳</div>
            <div class="text-xs text-gray-500 mb-2">+۱۲٪ نسبت به دیروز</div>
            <div id="spark1"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4 transition hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium">تحویل بار</h3>
                <span class="text-blue-500 text-xl">⬆</span>
            </div>
            <div class="text-2xl font-bold mb-1 mt-1">۲۳</div>
            <div class="text-xs text-gray-500 mb-2">+۸٪ افزایش</div>
            <div id="spark2"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4 transition hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium">کاربران جدید</h3>
                <span class="text-yellow-500 text-xl">⬆</span>
            </div>
            <div class="text-2xl font-bold mb-1 mt-1">۱۱ نفر</div>
            <div class="text-xs text-gray-500 mb-2">+۵٪ نسبت به هفته پیش</div>
            <div id="spark3"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-8 m-24">
        <h2 class="text-xl font-bold mb-4">نمودار باربری ماهانه</h2>
        <div id="chart"></div>
    </div>
    <div class="flex-1 flex flex-row gap-6 p-8 justify-around">
        <div class="bg-white rounded-2xl shadow-lg m-6 p-8 w-3/5">
            <h2 class="text-xl font-bold mb-4">وضعیت بار ها </h2>
            <div id="polarAreaChart"></div>
        </div>

        <div class="w-2/5 m-6 p-4 bg-white border border-gray-200 rounded-lg  shadow-lg sm:p-8  ">
            <div class="flex items-center justify-between mb-4 ">
                <h5 class="text-xl font-bold leading-none text-gray-900 ">فعال ترین صاحبان بار اخیر </h5>
                <p class="text-sm font-medium text-gray-900 ">
                    تعداد ثبت بار
                </p>
            </div>
            <div class="flow-root ">
                <ul role="list" class="divide-y divide-gray-200 ">
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <img src="data:image/png;base64,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" alt="user-male-circle--v1">
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate ">
                                    نیلوفر زمانی
                                </p>
                                <p class="text-sm text-gray-500 truncate ">
                                    ۰۹۱۳۴۲۵۶۳۷۴
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                                ۱۴۰
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center ">
                            <div class="shrink-0">
                                <img src="data:image/png;base64,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" alt="user-male-circle--v1">
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate ">
                                    بهرام مردانی
                                </p>
                                <p class="text-sm text-gray-500 truncate ">
                                    ۰۹۳۶۷۶۲۴۳۲۱
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                                ۱۰۴
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <img src="data:image/png;base64,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" alt="user-male-circle--v1">
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate ">
                                    محمد امیری
                                </p>
                                <p class="text-sm text-gray-500 truncate ">
                                    ۰۹۱۳۷۲۳۸۹۶۴
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                                ۹۳
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center ">
                            <div class="shrink-0">
                                <img src="data:image/png;base64,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" alt="user-male-circle--v1">
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate ">
                                    الهه عباسی
                                </p>
                                <p class="text-sm text-gray-500 truncate ">
                                    ۰۹۹۱۶۵۳۹۴۵۷
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                                ۷۵
                            </div>
                        </div>
                    </li>
                    <li class="pt-3 pb-0 sm:pt-4">
                        <div class="flex items-center ">
                            <div class="shrink-0">
                                <img src="data:image/png;base64,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" alt="user-male-circle--v1">
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate ">
                                    ارشیا تهرانی
                                </p>
                                <p class="text-sm text-gray-500 truncate ">
                                    ۰۹۳۶۸۹۵۶۳۵۲
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                                ۵۹
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>

@endsection
