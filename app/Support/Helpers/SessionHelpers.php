<?php

use App\Models\User;
use App\Services\User\UserService;

if (! function_exists('getUserFromSession')) {
    /**
     * Retrieve the currently authenticated user from the session.
     *
     * @return User|null
     */
    function getUserFromSession(): ?User
    {
        return app(UserService::class)->getUserFromSession();
    }
}

if (! function_exists('requireUserFromSession')) {
    /**
     * Retrieve the currently authenticated user from the session or abort.
     *
     * @return User
     */
    function requireUserFromSession(): User
    {
        return app(UserService::class)->requireUserFromSession();
    }
}
