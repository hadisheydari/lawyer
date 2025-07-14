<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthorizationHelper
{
    /**
     * Abort with 403 if the authenticated user is not admin.
     */
    public function checkAdminAccess(): void
    {
        if (! $this->isAdmin()) {
            abort(403, __('messages.unauthorized_access'));
        }
    }

    /**
     * Determine if the authenticated user is admin.
     */
    public function isAdmin(): bool
    {
        $user = Auth::user();
        return $user && $user->accessLevel === 'admin';
    }
}
