<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->sortByDesc(fn($conv) => $conv->latestMessage
                ? $conv->latestMessage->created_at
                : $conv->created_at
            );

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
            ->sortByDesc(fn($conv) => $conv->latestMessage
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

        // بررسی باز بودن مکالمه
        if ($conversation->status !== 'active') {
            return back()->with('error', 'این مکالمه بسته شده است.');
        }

        $request->validate([
            'message'    => 'required_without:attachment|nullable|string|max:2000',
            'attachment' => 'required_without:message|nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ], [
            'message.required_without'    => 'لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.',
            'attachment.required_without' => 'لطفاً متن پیام را بنویسید یا یک فایل انتخاب کنید.',
            'attachment.mimes'            => 'فرمت فایل مجاز نیست.',
            'attachment.max'              => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        $attachmentData = null;

        if ($request->hasFile('attachment')) {
            $file     = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('chat_files', $fileName, 'public');

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
            'message'     => $request->message,
            'attachments' => $attachmentData,
            'is_read'     => false,
        ]);

        $conversation->touch();

        return back();
    }

    // ─── بستن مکالمه ─────────────────────────────────────────────────────────
    public function close($id)
    {
        $lawyer = $this->lawyer();

        $conversation = ChatConversation::where('lawyer_id', $lawyer->id)->findOrFail($id);

        $conversation->update([
            'status'    => 'closed',
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
            'status'    => 'active',
            'closed_at' => null,
        ]);

        return back()->with('success', 'مکالمه دوباره باز شد.');
    }
}