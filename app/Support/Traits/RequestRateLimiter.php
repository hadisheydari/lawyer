<?php

namespace App\Support\Traits;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait RequestRateLimiter
{
    protected int $maxAttempts = 3;

    /**
     * Ensures the user is not rate-limited and throws an exception if they are.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(string $key): void
    {
        $throttleKey = $this->throttleKey($key);

        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $this->handleRateLimitExceeded($throttleKey);
        }
    }

    /**
     * Generates a unique throttle key based on the user's key and IP address.
     */
    protected function throttleKey(string $key): string
    {
        return Str::transliterate(Str::lower($key).'|'.request()->ip());
    }

    /**
     * Handles the event when rate limiting is exceeded.
     *
     * @throws ValidationException
     */
    private function handleRateLimitExceeded(string $throttleKey): void
    {
        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($throttleKey);
        session()->put('throttle_expired_at', now()->addSeconds($seconds));

        throw ValidationException::withMessages([
            'auth' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
