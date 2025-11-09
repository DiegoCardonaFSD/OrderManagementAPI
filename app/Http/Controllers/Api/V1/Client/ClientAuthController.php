<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientLoginRequest;
use App\Services\Client\ClientAuthService;
use Illuminate\Http\JsonResponse;

class ClientAuthController extends Controller
{
    protected ClientAuthService $service;

    public function __construct(ClientAuthService $service)
    {
        $this->service = $service;
    }

    public function login(ClientLoginRequest $request): JsonResponse
    {
        $tenantId = (int) $request->header('X-Tenant-ID', 0);

        $result = $this->service->login($request->validated(), $tenantId);

        return response()->json($result);
    }
}
