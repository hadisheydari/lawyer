<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function index(Request $request)
    {
        $lawyers  = Lawyer::active()->get();
        $services = Service::active()->get();

        $preSelectedLawyer  = $request->query('lawyer');
        $preSelectedService = $request->query('service');

        return view('public.reserve.index', compact(
            'lawyers', 'services', 'preSelectedLawyer', 'preSelectedService'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'phone'     => 'required|string|max:20',
            'lawyer_id' => 'required|exists:lawyers,id',
            'service_id'=> 'nullable|exists:services,id',
            'date'      => 'required|date',
            'time_slot' => 'nullable|string|max:10',
            'notes'     => 'nullable|string|max:500',
        ], [
            'name.required'      => 'نام الزامی است.',
            'phone.required'     => 'شماره تماس الزامی است.',
            'lawyer_id.required' => 'انتخاب وکیل الزامی است.',
            'date.required'      => 'تاریخ نوبت الزامی است.',
        ]);

        $reservation = Reservation::create([
            'name'          => $validated['name'],
            'phone'         => $validated['phone'],
            'lawyer_id'     => $validated['lawyer_id'],
            'service_id'    => $validated['service_id'] ?? null,
            'reserved_date' => $validated['date'],
            'time_slot'     => $validated['time_slot'] ?? null,
            'notes'         => $validated['notes'] ?? null,
        ]);

        // TODO: ارسال SMS با کد پیگیری: $reservation->tracking_code

        return back()->with('success', "نوبت شما با کد پیگیری {$reservation->tracking_code} ثبت شد.");
    }
}
