<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Notifications\NewChatMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    // ─── لیست مکالمات ────────────────────────────────────────────────────────
    public function index()
    {
        $lawyer = $this->lawyer();

        $conversations = ChatConversation::with(['user', 'latestMessage', 'consultation', 'case'])
            ->where('lawyer_id', $lawyer->id)
            ->get()
            ->sortByDesc(fn($conv) => $conv->latestMessage ? $conv->latestMessage->created_at : $conv->created_at);

        return view('lawyer.chat.index', compact('conversations'));
    }

    // ─── نمایش یک مکالمه ─────────────────────────────────────────────────────
    public function show($id)
    {
        $lawyer = $this->lawyer();

        $activeConversation = ChatConversation::with(['user', 'consultation', 'case'])
            ->where('lawyer_id', $lawyer->id)
            ->findOrFail($id);

        // علامت‌گذاری پیام‌های موکل به عنوان خوانده‌شده
        $activeConversation->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = $activeConversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        $conversations = ChatConversation::with(['user', 'latestMessage'])
            ->where('lawyer_id', $lawyer->id)
            ->get()
            ->sortByDesc(fn ($conv) => $conv->latestMessage
                ? $conv->latestMessage->created_at
                : $conv->created_at
            );

        return view('lawyer.chat.index', compact(
            'conversations',
            'activeConversation',
            'messages'
        ));
    }

    // ─── ارسال پیام ──────────────────────────────────────────────────────────
    public function send(Request $request, $id)
    {
        $lawyer = $this->lawyer();

        $conversation = ChatConversation::where('lawyer_id', $lawyer->id)->findOrFail($id);

        if ($conversation->status !== 'active') {
            return back()->with('error', 'این مکالمه بسته شده است.');
        }

        $request->validate([
            'message'    => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ], [
            'attachment.mimes' => 'فرمت فایل مجاز نیست.',
            'attachment.max'   => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        $messageText = trim((string) $request->input('message'));
        $hasFile = $request->hasFile('attachment');

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
            'sender_id'   => $lawyer->id,
            'sender_type' => 'lawyer',
            'message'     => $messageText, // ✅ همیشه رشته است، هرگز null نمی‌شود
            'attachments' => $attachmentData,
            'is_read'     => false,
        ]);

        $conversation->touch();
        $conversation->user?->notify(new NewChatMessageNotification(
            $lawyer->name,
            $messageText !== '' ? $messageText : 'یک فایل ارسال شد',
            route('client.chat.show', $conversation->id)
        ));

        return back();
    }

    // ─── بستن مکالمه ─────────────────────────────────────────────────────────
    public function close($id)
    {
        $lawyer = $this->lawyer();

        $conversation = ChatConversation::where('lawyer_id', $lawyer->id)->findOrFail($id);

        $conversation->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return back()->with('success', 'مکالمه بسته شد.');
    }

    // ─── باز کردن مجدد مکالمه ───────────────────────────────────────────────
    public function reopen($id)
    {
        $lawyer = $this->lawyer();

        $conversation = ChatConversation::where('lawyer_id', $lawyer->id)->findOrFail($id);

        $conversation->update([
            'status' => 'active',
            'closed_at' => null,
        ]);

        return back()->with('success', 'مکالمه دوباره باز شد.');
    }

    // ─── دانلود امن فایل پیوست ───────────────────────────────────────────────
    public function downloadAttachment($id, $messageId)
    {
        $lawyer = $this->lawyer();

        $conversation = ChatConversation::where('lawyer_id', $lawyer->id)
            ->findOrFail($id);

        $message = $conversation->messages()->findOrFail($messageId);

        $attachment = $message->attachments[0] ?? null;

        abort_if(!$attachment, 404, 'فایلی برای این پیام یافت نشد.');

        $path = $attachment['path'];

        abort_unless(
            Storage::disk('public')->exists($path),
            404,
            'فایل مورد نظر روی سرور یافت نشد.'
        );

        return response()->download(
            storage_path('app/public/' . $path),
            $attachment['name'] ?? basename($path)
        );
    }

    // ─── حذف پیام (فقط فرستنده می‌تواند پیام خودش را حذف کند) ───────────────
    public function destroyMessage($id, $messageId)
    {
        $lawyer = $this->lawyer();

        $conversation = ChatConversation::where('lawyer_id', $lawyer->id)->findOrFail($id);
        $message = $conversation->messages()->findOrFail($messageId);

        if ($message->sender_type !== 'lawyer' || $message->sender_id !== $lawyer->id) {
            return response()->json([
                'success' => false,
                'message' => 'شما فقط می‌توانید پیام‌های خودتان را حذف کنید.',
            ], 403);
        }

        // حذف فایل فیزیکی پیوست در صورت وجود
        if ($message->attachments) {
            foreach ($message->attachments as $att) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($att['path'] ?? '');
            }
        }

        $message->delete();

        return response()->json(['success' => true, 'message' => 'پیام حذف شد.']);
    }
}
