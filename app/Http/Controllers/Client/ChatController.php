<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\Lawyer;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // ۱. نمایش صفحه اصلی چت (لیست پیام‌ها و فرم انتخاب وکیل)
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

        // دریافت لیست وکلای فعال برای شروع چت جدید
        $lawyers = Lawyer::where('is_active', true)->get();

        return view('client.chat.index', compact('conversations', 'lawyers'));
    }

    // ۲. ایجاد یک چت جدید و هدایت کاربر به صفحه چت
    public function store(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'required|exists:lawyers,id',
        ], [
            'lawyer_id.required' => 'لطفاً وکیل مورد نظر خود را انتخاب کنید.',
            'lawyer_id.exists' => 'وکیل انتخاب شده معتبر نیست.',
        ]);

        $userId = auth()->id();

        // ایجاد یک چت جدید یا پیدا کردن چت قبلی با همین وکیل
        $conversation = ChatConversation::firstOrCreate([
            'user_id' => $userId,
            'lawyer_id' => $request->lawyer_id,
        ]);

        return redirect()->route('client.chat.show', $conversation->id);
    }

    // ۳. نمایش چت فعال و پیام‌های داخل آن
    public function show($id)
    {
        $userId = auth()->id();

        $activeConversation = ChatConversation::with('lawyer')
            ->where('user_id', $userId)
            ->findOrFail($id);

        $activeConversation->messages()
            ->where('sender_type', 'lawyer') 
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $activeConversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        $conversations = ChatConversation::with(['lawyer', 'latestMessage'])
            ->where('user_id', $userId)
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->latestMessage ? $conv->latestMessage->created_at : $conv->created_at;
            });

        $lawyers = Lawyer::where('is_active', true)->get();

        return view('client.chat.index', compact(
            'conversations', 
            'activeConversation', 
            'messages',
            'lawyers'
        ));
    }


    // ۴. متد send اصلی خودتان (با قابلیت ذخیره درست فایل و sender_id)
    public function send(Request $request, $id)
    {
        $userId = auth()->id();

        // پیدا کردن مکالمه و بررسی اینکه حتماً متعلق به همین کاربر باشد
        $conversation = ChatConversation::where('user_id', $userId)->findOrFail($id);

        // اعتبارسنجی ورودی‌ها
        $request->validate([
            'message'    => 'required_without:attachment|nullable|string|max:2000',
            'attachment' => 'required_without:message|nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ], [
            'message.required_without'    => 'لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.',
            'attachment.required_without' => 'لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.',
            'attachment.mimes'            => 'فرمت فایل ارسالی مجاز نیست (فقط تصاویر و اسناد).',
            'attachment.max'              => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        $attachmentData = null;

        // بررسی و آپلود فایل پیوست
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // ذخیره فایل
            $path = $file->storeAs('chat_files', $fileName, 'public');

            $attachmentData = [
                [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]
            ];
        }

        // ایجاد پیام (استفاده از relations که به صورت خودکار conversation_id را پر می‌کند)
        $conversation->messages()->create([
            'sender_id'   => $userId,      // این همان فیلدی است که ارور می‌داد
            'sender_type' => 'user',       
            'message'     => $request->message,
            'attachments' => $attachmentData,
            'is_read'     => false,        
        ]);

        $conversation->touch();

        return back();
    }
}