<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreOrderRequest;
use App\Services\Client\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
        //middleware de sanctum?
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $user = $request->user();
        $tenantId = (int) $request->header('X-Tenant-ID');

        $order = $this->service->storeOrder(
            clientId: $tenantId,
            userId: $user->id,
            items: $request->items
        );

        return response()->json([
            'success' => true,
            'message' => __('api.orders.created'),
            'data'    => $order,
        ], 201);
    }
}
