<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\CaseDocument;
use App\Models\CaseInstallment;
use App\Models\CaseStatusLog;
use App\Models\LegalCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class CaseController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    // ─── لیست پرونده‌ها ───────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $lawyer = $this->lawyer();

        $query = LegalCase::where('lawyer_id', $lawyer->id)
            ->with(['user', 'statusLogs' => fn($q) => $q->latest()->take(1)]);

        // فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        // جستجو
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('case_number', 'like', '%' . $request->search . '%');
            });
        }

        $cases = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'active'  => LegalCase::where('lawyer_id', $lawyer->id)->where('current_status', 'active')->count(),
            'on_hold' => LegalCase::where('lawyer_id', $lawyer->id)->where('current_status', 'on_hold')->count(),
            'closed'  => LegalCase::where('lawyer_id', $lawyer->id)->whereIn('current_status', ['closed', 'won', 'lost'])->count(),
            'total'   => LegalCase::where('lawyer_id', $lawyer->id)->count(),
        ];

        return view('lawyer.cases.index', compact('cases', 'stats'));
    }

    // ─── نمایش جزئیات پرونده ─────────────────────────────────────────────────
    public function show(LegalCase $case)
    {
        $this->authorizeCase($case);

        $case->load([
            'user',
            'service',
            'statusLogs.documents',
            'statusLogs.lawyer',
            'installments',
            'conversation',
        ]);

        return view('lawyer.cases.show', compact('case'));
    }

    // ─── فرم ایجاد پرونده جدید ───────────────────────────────────────────────
    public function create()
    {
        $lawyer   = $this->lawyer();
        $clients  = User::where('user_type', 'special')
                        ->where('upgraded_by', $lawyer->id)
                        ->orWhere('status', 'active')
                        ->orderBy('name')
                        ->get();
        $services = Service::active()->get();

        return view('lawyer.cases.create', compact('clients', 'services'));
    }

    // ─── ذخیره پرونده جدید ───────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'service_id'     => 'nullable|exists:services,id',
            'total_fee'      => 'required|numeric|min:0',
            'opened_at'      => 'nullable|date',
        ], [
            'user_id.required'    => 'انتخاب موکل الزامی است.',
            'title.required'      => 'عنوان پرونده الزامی است.',
            'total_fee.required'  => 'حق‌الوکاله الزامی است.',
        ]);

        $lawyer = $this->lawyer();

        $case = LegalCase::create([
            'case_number'    => LegalCase::generateCaseNumber(),
            'user_id'        => $request->user_id,
            'lawyer_id'      => $lawyer->id,
            'service_id'     => $request->service_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'current_status' => 'active',
            'total_fee'      => $request->total_fee,
            'paid_amount'    => 0,
            'opened_at'      => $request->opened_at ?? now(),
        ]);

        // ارتقا موکل به special اگر هنوز simple است
        $user = User::find($request->user_id);
        if ($user && $user->isSimple()) {
            $user->update([
                'user_type'    => 'special',
                'upgraded_by'  => $lawyer->id,
                'upgraded_at'  => now(),
            ]);
        }

        return redirect()->route('lawyer.cases.show', $case)
            ->with('success', 'پرونده با شماره ' . $case->case_number . ' ایجاد شد.');
    }

    // ─── فرم ویرایش پرونده ───────────────────────────────────────────────────
    public function edit(LegalCase $case)
    {
        $this->authorizeCase($case);
        $services = Service::active()->get();

        return view('lawyer.cases.edit', compact('case', 'services'));
    }

    // ─── به‌روزرسانی پرونده ───────────────────────────────────────────────────
    public function update(Request $request, LegalCase $case)
    {
        $this->authorizeCase($case);

        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'service_id'     => 'nullable|exists:services,id',
            'current_status' => 'required|in:active,on_hold,closed,won,lost',
            'total_fee'      => 'required|numeric|min:0',
            'paid_amount'    => 'required|numeric|min:0',
        ]);

        $data = $request->only([
            'title', 'description', 'service_id',
            'current_status', 'total_fee', 'paid_amount',
        ]);

        // اگر بسته شد، تاریخ بستن ثبت شود
        if (in_array($request->current_status, ['closed', 'won', 'lost']) && !$case->closed_at) {
            $data['closed_at'] = now();
        }

        $case->update($data);

        return redirect()->route('lawyer.cases.show', $case)
            ->with('success', 'اطلاعات پرونده به‌روز شد.');
    }

    // ─── ثبت لاگ وضعیت جدید (نوار پیشرفت موکل) ─────────────────────────────
    public function addStatusLog(Request $request, LegalCase $case)
    {
        $this->authorizeCase($case);

        $request->validate([
            'status_title'  => 'required|string|max:255',
            'description'   => 'nullable|string',
            'private_notes' => 'nullable|string',
            'status_date'   => 'required|date',
            'documents.*'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ], [
            'status_title.required' => 'عنوان وضعیت الزامی است.',
            'status_date.required'  => 'تاریخ الزامی است.',
        ]);

        DB::transaction(function () use ($request, $case) {
            $log = CaseStatusLog::create([
                'case_id'       => $case->id,
                'lawyer_id'     => $this->lawyer()->id,
                'status_title'  => $request->status_title,
                'description'   => $request->description,
                'private_notes' => $request->private_notes,
                'status_date'   => $request->status_date,
            ]);

            // آپلود اسناد ضمیمه
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('case_documents/' . $case->id, 'public');
                    CaseDocument::create([
                        'case_id'               => $case->id,
                        'status_log_id'         => $log->id,
                        'title'                 => $file->getClientOriginalName(),
                        'file_path'             => $path,
                        'file_type'             => $file->getClientOriginalExtension(),
                        'file_size'             => $file->getSize(),
                        'uploader_type'         => 'lawyer',
                        'uploader_id'           => $this->lawyer()->id,
                        'is_visible_to_client'  => true,
                    ]);
                }
            }
        });

        return back()->with('success', 'وضعیت جدید ثبت شد.');
    }

    // ─── ایجاد اقساط پرونده ──────────────────────────────────────────────────
    public function addInstallment(Request $request, LegalCase $case)
    {
        $this->authorizeCase($case);

        $request->validate([
            'installments'               => 'required|array|min:1',
            'installments.*.amount'      => 'required|numeric|min:1000',
            'installments.*.due_date'    => 'required|date',
            'installments.*.notes'       => 'nullable|string|max:500',
        ], [
            'installments.required' => 'حداقل یک قسط وارد کنید.',
        ]);

        // حذف اقساط قبلی پرداخت‌نشده
        $case->installments()->where('status', 'pending')->delete();

        foreach ($request->installments as $index => $inst) {
            CaseInstallment::create([
                'case_id'             => $case->id,
                'user_id'             => $case->user_id,
                'installment_number'  => $index + 1,
                'amount'              => $inst['amount'],
                'due_date'            => $inst['due_date'],
                'status'              => 'pending',
                'notes'               => $inst['notes'] ?? null,
            ]);
        }

        return back()->with('success', 'اقساط با موفقیت ثبت شدند.');
    }

    // ─── حذف پرونده ──────────────────────────────────────────────────────────
    public function destroy(LegalCase $case)
    {
        $this->authorizeCase($case);

        // فقط پرونده‌های بسته قابل حذف هستند
        if (!$case->isClosed()) {
            return back()->with('error', 'فقط پرونده‌های بسته قابل حذف هستند.');
        }

        $case->delete();

        return redirect()->route('lawyer.cases.index')
            ->with('success', 'پرونده حذف شد.');
    }

    // ─── بررسی مالکیت پرونده ─────────────────────────────────────────────────
    private function authorizeCase(LegalCase $case): void
    {
        if ($case->lawyer_id !== $this->lawyer()->id) {
            abort(403, 'شما دسترسی به این پرونده را ندارید.');
        }
    }
}