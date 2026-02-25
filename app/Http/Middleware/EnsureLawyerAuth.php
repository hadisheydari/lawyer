<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * بررسی لاگین بودن وکیل از طریق guard مخصوص
 */
class EnsureLawyerAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('lawyer')->check()) {
            return redirect()->route('lawyer.login')
                ->with('error', 'لطفاً وارد پنل وکیل شوید.');
        }

        return $next($request);
    }
}