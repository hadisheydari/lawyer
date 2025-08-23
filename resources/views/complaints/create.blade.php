@extends('layouts.main')
@section('header', 'بررسی شکایات')
@section('content')

    <div class="relative w-full h-full flex flex-col !rounded ">

        <div class="flex flex-col h-full bg-white !rounded-xl shadow-lg">

            <!-- هدر چت -->
            <div class="bg-blue-600 text-white text-center font-bold py-3 rounded-t-xl">
                چت با بات
            </div>

            <!-- پیام‌ها -->
            <div id="chatMessages" class="flex-1 flex flex-col gap-3 overflow-y-auto p-4" style="direction: ltr;">

                <!-- پیام بات سمت راست -->
                <div style="align-self: flex-end; background-color: #4B5563; color: white; border-radius: 0.75rem; padding: 0.75rem; width: 40%; word-break: break-word;">
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">بات:</div>
                    <div>سلام! چگونه می‌توانم کمکتان کنم؟</div>
                </div>

                <!-- پیام کاربر سمت چپ -->
                <div style="align-self: flex-start; background-color: #2563EB; color: white; border-radius: 0.75rem; padding: 0.75rem; width: 40%; word-break: break-word;">
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">شما:</div>
                    <div>سلام! می‌خواهم اطلاعاتی دریافت کنم.</div>
                </div>

            </div>

            <!-- ورودی پیام -->
            <div class="flex p-3 border-t border-gray-300 bg-white">
                <input type="text" placeholder="پیام خود را بنویسید..."
                       class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition">
                    ارسال
                </button>
            </div>

        </div>

    </div>

@endsection
