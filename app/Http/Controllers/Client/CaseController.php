<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\CaseInstallment;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    // ─── لیست پرونده‌های موکل ویژه ───────────────────────────────────────────
    public function index()
    {
        $user = Auth::user();

        if (!$user->isSpecial()) {
            abort(403, 'این بخش فقط برای موکلین ویژه در دسترس است.');
        }

        $cases = LegalCase::where('user_id', $user->id)
            ->with([
                'lawyer',
                'service',
                'statusLogs' => fn($q) => $q->latest('status_date')->take(1),
            ])
            ->latest()
            ->paginate(10);

        $activeCases = LegalCase::where('user_id', $user->id)
            ->where('current_status', 'active')
            ->count();

        $closedCases = LegalCase::where('user_id', $user->id)
            ->whereIn('current_status', ['closed', 'won', 'lost'])
            ->count();

        return view('client.cases.index', compact('cases', 'activeCases', 'closedCases'));
    }

    // ─── جزئیات پرونده ───────────────────────────────────────────────────────
    public function show(LegalCase $case)
    {
        $user = Auth::user();

        if ($case->user_id !== $user->id) {
            abort(403);
        }

        $case->load([
            'lawyer',
            'service',
            'statusLogs' => fn($q) => $q->with('documents')->latest('status_date'),
            'installments',
            'conversation',
        ]);

        // اسناد قابل نمایش برای موکل
        $visibleDocuments = $case->documents()
            ->where('is_visible_to_client', true)
            ->latest()
            ->get();

        // قسط بعدی
        $nextInstallment = $case->installments()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();

        return view('client.cases.show', compact('case', 'visibleDocuments', 'nextInstallment'));
    }
}