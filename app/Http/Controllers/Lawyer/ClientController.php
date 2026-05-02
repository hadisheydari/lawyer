<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LegalCase;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    // ─── لیست موکلین ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $lawyer = $this->lawyer();

        // موکلین ویژه که توسط این وکیل ارتقا پیدا کردند
        $specialClientsQuery = User::where('user_type', 'special')
            ->where('upgraded_by', $lawyer->id)
            ->with(['cases' => fn($q) => $q->where('lawyer_id', $lawyer->id)->latest()->take(1)]);

        // موکلین ساده که با این وکیل مشاوره داشتند
        $simpleClientsQuery = User::where('user_type', 'simple')
            ->whereHas('consultations', fn($q) => $q->where('lawyer_id', $lawyer->id));

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $specialClientsQuery->where(fn($q) => $q->where('name', 'like', $term)->orWhere('phone', 'like', $term));
            $simpleClientsQuery->where(fn($q) => $q->where('name', 'like', $term)->orWhere('phone', 'like', $term));
        }

        $type = $request->get('type', 'all');

        if ($type === 'special') {
            $clients = $specialClientsQuery->latest()->paginate(20)->withQueryString();
        } elseif ($type === 'simple') {
            $clients = $simpleClientsQuery->latest()->paginate(20)->withQueryString();
        } else {
            // ترکیب هر دو
            $specialIds = (clone $specialClientsQuery)->pluck('id');
            $simpleIds  = (clone $simpleClientsQuery)->pluck('id');
            $allIds     = $specialIds->merge($simpleIds)->unique();

            $clients = User::whereIn('id', $allIds)
                ->with(['cases' => fn($q) => $q->where('lawyer_id', $lawyer->id)->latest()->take(1)])
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        $stats = [
            'special_count' => User::where('user_type', 'special')->where('upgraded_by', $lawyer->id)->count(),
            'simple_count'  => User::where('user_type', 'simple')
                ->whereHas('consultations', fn($q) => $q->where('lawyer_id', $lawyer->id))->count(),
        ];

        return view('lawyer.clients.index', compact('clients', 'stats', 'type'));
    }

    // ─── جزئیات موکل ─────────────────────────────────────────────────────────
    public function show(User $client)
    {
        $lawyer = $this->lawyer();

        // بررسی دسترسی: موکل باید با این وکیل در ارتباط باشد
        $hasRelation = $client->upgraded_by === $lawyer->id
            || $client->consultations()->where('lawyer_id', $lawyer->id)->exists()
            || $client->cases()->where('lawyer_id', $lawyer->id)->exists();

        if (!$hasRelation) {
            abort(403, 'شما دسترسی به اطلاعات این موکل را ندارید.');
        }

        $cases = LegalCase::where('user_id', $client->id)
            ->where('lawyer_id', $lawyer->id)
            ->with(['statusLogs' => fn($q) => $q->latest()->take(1)])
            ->latest()
            ->get();

        $consultations = Consultation::where('user_id', $client->id)
            ->where('lawyer_id', $lawyer->id)
            ->latest()
            ->take(10)
            ->get();

        return view('lawyer.clients.show', compact('client', 'cases', 'consultations'));
    }

    // ─── ارتقا موکل از simple به special ─────────────────────────────────────
    public function upgrade(Request $request, User $client)
    {
        $lawyer = $this->lawyer();

        if (!$client->isSimple()) {
            return back()->with('error', 'این کاربر از قبل موکل ویژه است.');
        }

        $client->update([
            'user_type'   => 'special',
            'upgraded_by' => $lawyer->id,
            'upgraded_at' => now(),
        ]);

        return back()->with('success', $client->name . ' به موکل ویژه ارتقا یافت.');
    }
}