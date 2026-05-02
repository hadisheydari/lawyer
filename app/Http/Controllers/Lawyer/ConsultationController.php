<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    // ─── لیست مشاوره‌ها ───────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $lawyer = $this->lawyer();

        $query = Consultation::where('lawyer_id', $lawyer->id)
            ->with('user');

        // فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فیلتر نوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // جستجو بر اساس نام موکل
        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%'));
        }

        $consultations = $query->latest('scheduled_at')->paginate(20)->withQueryString();

        $stats = [
            'pending'    => Consultation::where('lawyer_id', $lawyer->id)->where('status', 'pending')->count(),
            'confirmed'  => Consultation::where('lawyer_id', $lawyer->id)->where('status', 'confirmed')->count(),
            'completed'  => Consultation::where('lawyer_id', $lawyer->id)->where('status', 'completed')->count(),
            'cancelled'  => Consultation::where('lawyer_id', $lawyer->id)->where('status', 'cancelled')->count(),
        ];

        return view('lawyer.consultations.index', compact('consultations', 'stats'));
    }

    // ─── جزئیات مشاوره ───────────────────────────────────────────────────────
    public function show(Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);
        $consultation->load(['user', 'service', 'payment']);

        return view('lawyer.consultations.show', compact('consultation'));
    }

    // ─── تأیید مشاوره ────────────────────────────────────────────────────────
    public function confirm(Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);

        if (!$consultation->isPending()) {
            return back()->with('error', 'این مشاوره قابل تأیید نیست.');
        }

        $consultation->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // ایجاد مکالمه چت اگر وجود ندارد
        ChatConversation::firstOrCreate(
            ['consultation_id' => $consultation->id],
            [
                'user_id'   => $consultation->user_id,
                'lawyer_id' => $consultation->lawyer_id,
                'title'     => 'مشاوره: ' . $consultation->title,
                'status'    => 'active',
            ]
        );

        return back()->with('success', 'مشاوره تأیید شد و چت باز شد.');
    }

    // ─── رد مشاوره ───────────────────────────────────────────────────────────
    public function reject(Request $request, Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ], [
            'cancellation_reason.required' => 'لطفاً دلیل رد را بنویسید.',
        ]);

        if (!$consultation->isPending()) {
            return back()->with('error', 'این مشاوره قابل رد کردن نیست.');
        }

        $consultation->update([
            'status'               => 'rejected',
            'cancellation_reason'  => $request->cancellation_reason,
            'cancelled_at'         => now(),
        ]);

        return back()->with('success', 'مشاوره رد شد.');
    }

    // ─── تکمیل مشاوره ────────────────────────────────────────────────────────
    public function complete(Request $request, Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);

        $request->validate([
            'lawyer_notes' => 'nullable|string|max:2000',
        ]);

        if (!$consultation->isConfirmed()) {
            return back()->with('error', 'فقط مشاوره‌های تأیید شده قابل تکمیل هستند.');
        }

        $consultation->update([
            'status'        => 'completed',
            'lawyer_notes'  => $request->lawyer_notes,
            'completed_at'  => now(),
        ]);

        // بستن مکالمه چت مرتبط
        if ($consultation->conversation) {
            $consultation->conversation->update([
                'status'    => 'closed',
                'closed_at' => now(),
            ]);
        }

        return back()->with('success', 'مشاوره تکمیل شد.');
    }

    // ─── لغو مشاوره ──────────────────────────────────────────────────────────
    public function cancel(Request $request, Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ], [
            'cancellation_reason.required' => 'دلیل لغو الزامی است.',
        ]);

        if ($consultation->isCompleted() || $consultation->isCancelled()) {
            return back()->with('error', 'این مشاوره قابل لغو نیست.');
        }

        $consultation->update([
            'status'               => 'cancelled',
            'cancellation_reason'  => $request->cancellation_reason,
            'cancelled_at'         => now(),
        ]);

        return back()->with('success', 'مشاوره لغو شد.');
    }

    // ─── ثبت یادداشت وکیل ───────────────────────────────────────────────────
    public function addNote(Request $request, Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);

        $request->validate([
            'lawyer_notes' => 'required|string|max:2000',
        ], [
            'lawyer_notes.required' => 'یادداشت نمی‌تواند خالی باشد.',
        ]);

        $consultation->update(['lawyer_notes' => $request->lawyer_notes]);

        return back()->with('success', 'یادداشت ذخیره شد.');
    }

    // ─── بررسی مالکیت مشاوره ─────────────────────────────────────────────────
    private function authorizeConsultation(Consultation $consultation): void
    {
        if ($consultation->lawyer_id !== $this->lawyer()->id) {
            abort(403, 'شما دسترسی به این مشاوره را ندارید.');
        }
    }
}