<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientStoreRequest;
use App\Services\Admin\ClientService;
use Illuminate\Http\JsonResponse;

class AdminClientController extends Controller
{
    protected ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    public function store(ClientStoreRequest $request): JsonResponse
    {
        $client = $this->service->createClient($request->validated());

        return response()->json([
            'success' => true,
            'message' => __('api.admin.client.created'),
            'data' => $client
        ], 201);
    }
}
