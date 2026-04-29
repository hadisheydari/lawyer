<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('client.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'national_code' => 'nullable|digits:10|unique:users,national_code,' . $user->id,
        ], [
            'name.required'       => 'نام الزامی است.',
            'email.email'         => 'فرمت ایمیل صحیح نیست.',
            'email.unique'        => 'این ایمیل قبلاً ثبت شده است.',
            'national_code.digits'  => 'کد ملی باید ۱۰ رقم باشد.',
            'national_code.unique'  => 'این کد ملی قبلاً ثبت شده است.',
        ]);

        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'national_code' => $request->national_code,
        ]);

        return back()->with('success', 'اطلاعات با موفقیت بروزرسانی شد.');
    }
}