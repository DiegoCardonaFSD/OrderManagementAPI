<?php

namespace App\Http\MIddleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        // Force JSON for all API requests
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
