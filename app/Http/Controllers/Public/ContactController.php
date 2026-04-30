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
            'service' => 'nullable|string|max:100',
            'email'   => 'nullable|email|max:100',
            'message' => 'nullable|string|max:1000',
        ], [
            'name.required'  => 'نام الزامی است.',
            'phone.required' => 'شماره تماس الزامی است.',
        ]);

        // ✅ Fix: map 'service' → 'subject' (table column), remove 'ip' (no column in migration)
        ContactRequest::create([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'subject' => $validated['service'] ?? 'درخواست مشاوره عمومی',
            'email'   => $validated['email'] ?? null,
            'message' => $validated['message'] ?? null,
        ]);

        return back()->with('success', 'درخواست شما ثبت شد. به زودی با شما تماس خواهیم گرفت.');
    }
}