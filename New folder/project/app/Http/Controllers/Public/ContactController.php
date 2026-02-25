<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact.index');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'service' => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:100',
            'message' => 'nullable|string|max:1000',
        ], [
            'name.required'  => 'نام الزامی است.',
            'phone.required' => 'شماره تماس الزامی است.',
        ]);

        ContactRequest::create(array_merge($validated, [
            'ip' => $request->ip(),
        ]));

        // TODO: ارسال نوتیفیکیشن به وکلا (Mail/SMS)
        // Notification::route('mail', config('office.email'))
        //     ->notify(new NewContactNotification($validated));

        return back()->with('success', 'درخواست شما ثبت شد. به زودی با شما تماس خواهیم گرفت.');
    }
}
