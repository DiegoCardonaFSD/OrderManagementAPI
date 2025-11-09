<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$scopes
     */
    public function handle(Request $request, Closure $next, ...$scopes)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => __('api.auth.unauthorized')
            ], 401);
        }

        foreach ($scopes as $scope) {
            if (! $user->tokenCan($scope)) {
                return response()->json([
                    'message' => __("api.auth.forbidden_scope", ['scope' => $scope])
                ], 403);

            }
        }

        return $next($request);
    }
}
