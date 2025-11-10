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

    public function show(int $id): JsonResponse
    {
        $tenantId = (int) request()->header('X-Tenant-ID');
        $user = request()->user();

        $order = $this->service->getOrder(
            orderId: $id,
            clientId: $tenantId
        );

        if (!$order || $order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => __('api.orders.not_found'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => __('api.orders.found'),
            'data' => $order,
        ]);
    }
}
