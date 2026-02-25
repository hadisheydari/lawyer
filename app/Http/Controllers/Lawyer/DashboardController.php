<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\Consultation;
use App\Models\User;
use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $lawyer = Auth::guard('lawyer')->user();

        $activeCases = LegalCase::where('lawyer_id', $lawyer->id)
            ->where('current_status', 'active')
            ->with(['user', 'statusLogs' => fn($q) => $q->latest()->take(1)])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'active_cases'    => LegalCase::where('lawyer_id', $lawyer->id)->where('current_status', 'active')->count(),
            'won_cases'       => LegalCase::where('lawyer_id', $lawyer->id)->where('current_status', 'won')->count(),
            'pending_consult' => Consultation::where('lawyer_id', $lawyer->id)->whereIn('status', ['pending', 'confirmed'])->count(),
            'unread_messages' => ChatConversation::where('lawyer_id', $lawyer->id)->count(), // ساده‌سازی
        ];

        $pendingConsultations = Consultation::where('lawyer_id', $lawyer->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('lawyer.dashboard.index', compact(
            'lawyer',
            'activeCases',
            'stats',
            'pendingConsultations'
        ));
    }

    public function logout(Request $request)
    {
        Auth::guard('lawyer')->logout();
        return redirect()->route('lawyer.login');
    }
}
