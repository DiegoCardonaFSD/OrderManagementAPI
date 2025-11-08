<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetApiLocale
{
    public function handle(Request $request, Closure $next)
    {
        // high priority: specific header
        if ($request->hasHeader('X-Language')) {
            App::setLocale($request->header('X-Language'));
        }
        // medium priority: Accept-Language
        elseif ($request->hasHeader('Accept-Language')) {
            $language = substr($request->header('Accept-Language'), 0, 2);
            App::setLocale($language);
        }

        return $next($request);
    }
}