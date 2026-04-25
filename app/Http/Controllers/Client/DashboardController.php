<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CaseInstallment;
use App\Models\ChatConversation;
use App\Models\Consultation;
use App\Models\LegalCase;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * نقطه ورود یکپارچه — بر اساس user_type، به ویو مناسب هدایت می‌کند
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSpecial()) {
            return $this->special($user);
        }

        return $this->simple($user);
    }

    // ─── داشبورد مشتری ساده ───────────────────────────────────────────────────
    private function simple($user)
    {
        $consultations = Consultation::where('user_id', $user->id)
            ->with('lawyer')
            ->latest()
            ->take(5)
            ->get();

        $activeConsultations = Consultation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->count();

        $completedConsultations = Consultation::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $unreadMessages = ChatConversation::where('user_id', $user->id)
            ->with('latestMessage')
            ->get()
            ->sum(fn($c) => $c->getUnreadCountFor('user', $user->id));

        return view('client.dashboard.simple', compact(
            'user',
            'consultations',
            'activeConsultations',
            'completedConsultations',
            'unreadMessages'
        ));
    }

    // ─── داشبورد موکل ویژه ────────────────────────────────────────────────────
    private function special($user)
    {
        $cases = LegalCase::where('user_id', $user->id)
            ->with(['lawyer', 'statusLogs' => fn($q) => $q->latest()->take(1)])
            ->latest()
            ->take(5)
            ->get();

        $activeCases = LegalCase::where('user_id', $user->id)
            ->where('current_status', 'active')
            ->count();

        $closedCases = LegalCase::where('user_id', $user->id)
            ->whereIn('current_status', ['closed', 'won', 'lost'])
            ->count();

        $pendingInstallments = CaseInstallment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->take(3)
            ->get();

        $overdueInstallments = CaseInstallment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();

        $unreadMessages = ChatConversation::where('user_id', $user->id)
            ->get()
            ->sum(fn($c) => $c->getUnreadCountFor('user', $user->id));

        $totalFee    = LegalCase::where('user_id', $user->id)->sum('total_fee');
        $totalPaid   = LegalCase::where('user_id', $user->id)->sum('paid_amount');
        $totalRemain = max(0, $totalFee - $totalPaid);

        return view('client.dashboard.special', compact(
            'user',
            'cases',
            'activeCases',
            'closedCases',
            'pendingInstallments',
            'overdueInstallments',
            'unreadMessages',
            'totalFee',
            'totalPaid',
            'totalRemain'
        ));
    }

    // ─── پروفایل ──────────────────────────────────────────────────────────────
    public function profile()
    {
        $user = Auth::user();
        return view('client.profile', compact('user'));
    }
}