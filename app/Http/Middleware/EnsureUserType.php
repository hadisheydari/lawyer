<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * بررسی دسترسی بر اساس user_type
 *
 * استفاده در routes:
 *   ->middleware('user.type:special')    → فقط موکل ویژه
 *   ->middleware('user.type:simple')     → فقط مشتری ساده
 *   ->middleware('user.type:any')        → هر کاربر لاگین شده
 */
class EnsureUserType
{
    public function handle(Request $request, Closure $next, string $type = 'any'): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($type !== 'any' && $user->user_type !== $type) {
            abort(403, 'شما مجاز به دسترسی این بخش نیستید.');
        }

        if ($user->status === 'blocked') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'حساب کاربری شما مسدود شده است.');
        }

        return $next($request);
    }
}
