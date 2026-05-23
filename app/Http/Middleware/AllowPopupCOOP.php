<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowPopupCOOP
{
    /**
     * Add Cross-Origin-Opener-Policy header allowing popups.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Allow opener to interact with popups opened to other origins
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        return $response;
    }
}
