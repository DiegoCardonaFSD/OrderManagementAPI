<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValidateTenant
{
    public function handle(Request $request, Closure $next)
    {
        $tenantId = $request->header('X-Tenant-ID');
        $user = $request->user();

        if (!$tenantId || $user->client_id != (int) $tenantId) {
            return response()->json([
                'success' => false,
                'message' => __('api.client.invalid_tenant')
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
