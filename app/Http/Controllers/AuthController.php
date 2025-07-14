<?php

namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(RegisterRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        auth()->login($user);

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return $this->success('ثبت نام و ورود با موفقیت انجام شد!', 201, [
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => new UserResource($user),
            ]);
        }

        return redirect()->intended('/dashboard');
    }


    public function login(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) {
            if ($request->expectsJson()) {
                return $this->error('شماره موبایل یا رمز عبور اشتباه است.', 401);
            }

            return back()->withErrors([
                'username-or-password-wrong' => 'شماره موبایل یا رمز عبور اشتباه است.',
            ])->withInput();
        }

        $user = auth()->user();

        if ($request->expectsJson()) {
            return $this->success('ورود به بازارگاه با موفقیت انجام شد!', 200, [
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => new UserResource($user),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        // api token
        $user->tokens()->delete();
        // web session
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        if ($request->expectsJson()) {
            return $this->success('خروج با موفقیت انجام شد.');
        }

        return redirect('/login')->with('status', 'خروج با موفقیت انجام شد.');
    }
}
