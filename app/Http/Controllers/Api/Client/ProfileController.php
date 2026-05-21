<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // ─── دریافت پروفایل ───────────────────────────────────────────────────────
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'phone'         => $user->phone,
                'email'         => $user->email,
                'national_code' => $user->national_code,
                'user_type'     => $user->user_type,
                'status'        => $user->status,
                'upgraded_at'   => $user->upgraded_at,
            ],
        ]);
    }

    // ─── به‌روزرسانی پروفایل ─────────────────────────────────────────────────
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['nullable', 'email', 'unique:users,email,' . $user->id],
            'national_code' => ['nullable', 'digits:10', 'unique:users,national_code,' . $user->id],
        ], [
            'name.required'         => 'نام الزامی است.',
            'email.email'           => 'فرمت ایمیل صحیح نیست.',
            'email.unique'          => 'این ایمیل قبلاً ثبت شده است.',
            'national_code.digits'  => 'کد ملی باید ۱۰ رقم باشد.',
            'national_code.unique'  => 'این کد ملی قبلاً ثبت شده است.',
        ]);

        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'national_code' => $request->national_code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات با موفقیت بروزرسانی شد.',
            'user'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'phone'         => $user->phone,
                'email'         => $user->email,
                'national_code' => $user->national_code,
            ],
        ]);
    }
}








