<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserOtpResource;
use App\Http\Resources\UserResource;
use App\Models\City;
use App\Models\Province;
use App\Models\User;
use App\Services\OtpService;
use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\View\View;
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

        $otp = app(OtpService::class)->generate($user);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return $this->success('ثبت‌نام انجام شد، کد برای شما ارسال شد.', 201, [
                'otp' => new UserOtpResource($otp),
            ]);
        }

        $request->session()->put('phone', $user->phone);

        return redirect()->route('verify');
    }

    public function verify(OtpRequest $request, OtpService $otpService): JsonResponse|RedirectResponse
    {
        $phone = $request->session()->get('phone');
        $user = User::where('phone', $phone)->first();

        if ($otpService->verify($user, $request['code'])) {
            $request->session()->regenerate();
            if ($request->expectsJson()) {
                return $this->success('ثبت نام و ورود با موفقیت انجام شد!', 201, [
                    'token' => $user->createToken('auth_token')->plainTextToken,
                    'user' => new UserResource($user),
                ]);
            }

            return redirect()->route('selectRole')
                ->with('success', 'ورود موفق! نقش خود را انتخاب کنید.');
        }

        return back()->withErrors(['code' => 'کد وارد شده اشتباه یا منقضی شده است.']);
    }

    public function selectRole(Request $request, string $role, ?string $type = null): View|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->hasRole($role)) {
            $user->syncRoles([$role]);
        }

        if ($type !== null) {
            $request->session()->put('user_type', $type);
        }

        $user->save();

        $cities = City::all();
        $province = Province::all();

        return view('auth.role-info', compact('cities', 'province'))
            ->with('success', 'نقش شما با موفقیت ثبت شد.');

    }

    public function login(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $credentials = $request->validated();

        if (! auth()->attempt($credentials)) {
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
        $user->tokens()->delete();
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        if ($request->expectsJson()) {
            return $this->success('خروج با موفقیت انجام شد.');
        }

        return redirect('/login')->with('status', 'خروج با موفقیت انجام شد.');
    }
}
