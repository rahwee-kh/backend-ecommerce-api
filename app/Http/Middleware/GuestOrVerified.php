<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

class GuestOrVerified extends EnsureEmailIsVerified
{
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        // Allow guests
        if (! $request->user()) {
            return $next($request);
        }

        // Logged-in users must be verified
        return parent::handle($request, $next, $redirectToRoute);
    }
}
