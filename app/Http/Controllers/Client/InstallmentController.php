<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CaseInstallment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    // ─── لیست اقساط موکل ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user->isSpecial()) {
            abort(403, 'این بخش فقط برای موکلین ویژه در دسترس است.');
        }

        $query = CaseInstallment::where('user_id', $user->id)
            ->with(['case.lawyer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $installments = $query->orderByRaw("FIELD(status, 'pending', 'paid', 'overdue')")
            ->orderBy('due_date')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total_pending' => CaseInstallment::where('user_id', $user->id)->where('status', 'pending')->sum('amount'),
            'total_paid'    => CaseInstallment::where('user_id', $user->id)->where('status', 'paid')->sum('amount'),
            'overdue_count' => CaseInstallment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
            'next_due' => CaseInstallment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->orderBy('due_date')
                ->first(),
        ];

        return view('client.installments.index', compact('installments', 'stats'));
    }
}