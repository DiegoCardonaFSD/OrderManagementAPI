<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientStoreRequest;
use App\Services\Admin\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    protected ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $filters = $request->only(['name', 'email']);

        $clients = $this->service->listClients($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => __('api.admin.client.listed'),
            'data' => $clients
        ]);
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

    public function show(int $id): JsonResponse
    {
        $client = $this->service->getClientById($id);

        return response()->json([
            'success' => true,
            'message' => __('api.admin.client.found'),
            'data' => $client
        ]);
    }

}
