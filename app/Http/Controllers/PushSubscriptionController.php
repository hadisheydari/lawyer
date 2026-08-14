<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $subject = auth('lawyer')->check() ? auth('lawyer')->user() : auth('web')->user();

        if (! $subject) {
            return response()->json(['success' => false], 401);
        }

        $subject->updatePushSubscription(
            $request->endpoint,
            $request->input('keys.p256dh'),
            $request->input('keys.auth'),
            $request->input('contentEncoding')
        );

        return response()->json(['success' => true]);
    }
}