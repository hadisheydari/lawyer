<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\Lawyer;
use App\Notifications\NewChatMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $conversation = ChatConversation::where('user_id', $userId)->findOrFail($id);

        $request->validate([
            'message'    => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ], [
            'attachment.mimes' => 'فرمت فایل ارسالی مجاز نیست (فقط تصاویر و اسناد).',
            'attachment.max'   => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        $messageText = trim((string) $request->input('message'));
        $hasFile = $request->hasFile('attachment');

        // ✅ جلوگیری از ارسال کاملاً خالی
        if ($messageText === '' && !$hasFile) {
            return back()->with('error', 'لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.');
        }

        $attachmentData = null;

        if ($hasFile) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat_files', $fileName, 'public');

            $attachmentData = [[
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]];
        }

        $conversation->messages()->create([
            'sender_id'   => $userId,
            'sender_type' => 'user',
            'message'     => $messageText, // ✅ همیشه رشته است
            'attachments' => $attachmentData,
            'is_read'     => false,
        ]);

        $conversation->touch();
        $conversation->lawyer->notify(new NewChatMessageNotification(
            auth()->user()->name,
            $messageText !== '' ? $messageText : 'یک فایل ارسال شد',
            route('lawyer.chat.show', $conversation->id)
        ));

        return back();
    }

    // ─── دانلود امن فایل پیوست ───────────────────────────────────────────────
    public function downloadAttachment($id, $messageId)
    {
        $userId = auth()->id();

        $conversation = ChatConversation::where('user_id', $userId)->findOrFail($id);

        $message = $conversation->messages()->findOrFail($messageId);

        $attachment = $message->attachments[0] ?? null;

        abort_if(
            !$attachment,
            404,
            'فایلی برای این پیام یافت نشد.'
        );

        $path = $attachment['path'];

        abort_unless(
            Storage::disk('public')->exists($path),
            404,
            'فایل مورد نظر روی سرور یافت نشد.'
        );

        return response()->download(
            Storage::disk('public')->path($path),
            $attachment['name'] ?? basename($path)
        );
    }

    // ─── حذف پیام (فقط فرستنده می‌تواند پیام خودش را حذف کند) ───────────────
    public function destroyMessage($id, $messageId)
    {
        $userId = auth()->id();

        $conversation = ChatConversation::where('user_id', $userId)->findOrFail($id);
        $message = $conversation->messages()->findOrFail($messageId);

        if ($message->sender_type !== 'user' || $message->sender_id !== $userId) {
            return response()->json([
                'success' => false,
                'message' => 'شما فقط می‌توانید پیام‌های خودتان را حذف کنید.',
            ], 403);
        }

        if ($message->attachments) {
            foreach ($message->attachments as $att) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($att['path'] ?? '');
            }
        }

        $message->delete();

        return response()->json(['success' => true, 'message' => 'پیام حذف شد.']);
    }
}
