<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // متد index که قبلاً صحبت کردیم (فقط برای باز کردن صفحه خالی)
    public function index()
    {
        $userId = auth()->id();
        
        // لود کردن مکالمات برای سایدبار
        $conversations = ChatConversation::with(['lawyer', 'latestMessage'])
            ->where('user_id', $userId)
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->latestMessage ? $conv->latestMessage->created_at : $conv->created_at;
            });

        return view('client.chat.index', compact('conversations'));
    }

    // متد show برای باز کردن یک چت خاص
    public function show($id)
    {
        $userId = auth()->id();

        // ۱. پیدا کردن چت فعال (حتماً باید متعلق به همین کاربر باشد - امنیت)
        $activeConversation = ChatConversation::with('lawyer')
            ->where('user_id', $userId)
            ->findOrFail($id);

        // ۲. سین کردن پیام‌ها (پیام‌هایی که وکیل فرستاده و تا الان خوانده نشده‌اند را تیک دوم بزن)
        $activeConversation->messages()
            ->where('sender_type', 'lawyer') // فرض بر این است که تایپ وکیل 'lawyer' ذخیره می‌شود
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // ۳. دریافت تمام پیام‌های این چت (مرتب شده از قدیمی به جدید برای نمایش درست در چت)
        $messages = $activeConversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        // ۴. لود کردن دوباره لیست سایدبار (چون در ویو به متغیر $conversations نیاز داریم)
        $conversations = ChatConversation::with(['lawyer', 'latestMessage'])
            ->where('user_id', $userId)
            ->get()
            ->sortByDesc(function ($conv) {
                // مرتب‌سازی بر اساس تاریخ آخرین پیام
                return $conv->latestMessage ? $conv->latestMessage->created_at : $conv->created_at;
            });

        // ۵. ارسال اطلاعات به همان ویوی index
        return view('client.chat.index', compact(
            'conversations', 
            'activeConversation', 
            'messages'
        ));
    }


    // متد send برای ارسال پیام جدید و فایل ضمیمه
    public function send(Request $request, $id)
    {
        $userId = auth()->id();

        // ۱. پیدا کردن مکالمه و بررسی اینکه حتماً متعلق به همین کاربر باشد
        $conversation = ChatConversation::where('user_id', $userId)->findOrFail($id);

        // ۲. اعتبارسنجی ورودی‌ها (کاربر باید یا متن بفرستد یا فایل، یا هر دو)
        $request->validate([
            'message'    => 'required_without:attachment|nullable|string|max:2000',
            'attachment' => 'required_without:message|nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // حداکثر 5 مگابایت
        ], [
            'message.required_without'    => 'لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.',
            'attachment.required_without' => 'لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.',
            'attachment.mimes'            => 'فرمت فایل ارسالی مجاز نیست (فقط تصاویر و اسناد).',
            'attachment.max'              => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        $attachmentData = null;

        // ۳. بررسی و آپلود فایل پیوست
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            
            // تولید یک نام یکتا برای فایل جلوگیری از تداخل نام‌ها
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // ذخیره فایل در پوشه storage/app/public/chat_files
            $path = $file->storeAs('chat_files', $fileName, 'public');

            // آماده‌سازی آرایه اطلاعات فایل برای ذخیره در دیتابیس
            $attachmentData = [
                [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]
            ];
        }

        // ۴. ایجاد و ذخیره پیام در دیتابیس
        $conversation->messages()->create([
            'sender_id'   => $userId,      // در صورتی که نیاز به ثبت آیدی فرستنده دارید
            'sender_type' => 'user',       // مشخص می‌کند که پیام از طرف موکل است
            'message'     => $request->message,
            'attachments' => $attachmentData,
            'is_read'     => false,        // پیام جدید هنوز توسط وکیل خوانده نشده است
        ]);

        // ۵. بروزرسانی فیلد updated_at در مکالمه اصلی 
        // این کار باعث می‌شود این چت در سایدبار بیاید بالای لیست
        $conversation->touch();

        // ۶. ریدایرکت مجدد به همان صفحه با حفظ اسکرول (انجام شده توسط جاوااسکریپت)
        return back();
    }
}