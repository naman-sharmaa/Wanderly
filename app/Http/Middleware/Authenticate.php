<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Authenticate Middleware
 *
 * Redirects unauthenticated users to the login page.
 */
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
