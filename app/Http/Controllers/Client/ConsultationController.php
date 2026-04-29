<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $consultations = Consultation::where('user_id', $user->id)
            ->with('lawyer')
            ->latest()
            ->paginate(10);

        $activeCount    = Consultation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count();
        $completedCount = Consultation::where('user_id', $user->id)
            ->where('status', 'completed')->count();

        return view('client.consultations.index', compact(
            'consultations', 'activeCount', 'completedCount'
        ));
    }

    public function show(Consultation $consultation)
    {
        // فقط صاحب مشاوره دسترسی دارد
        if ($consultation->user_id !== Auth::id()) {
            abort(403);
        }

        $consultation->load('lawyer');

        return view('client.consultations.show', compact('consultation'));
    }
}