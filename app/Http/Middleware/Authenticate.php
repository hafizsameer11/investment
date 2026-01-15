<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // If the request is for admin routes, redirect to admin login
        if ($request->is('admin*') || $request->routeIs('admin.*')) {
            return route('admin.login');
        }

        // Otherwise, redirect to dashboard login
        return route('login');
    }
}
