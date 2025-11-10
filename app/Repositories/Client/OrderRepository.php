<?php

namespace App\Repositories\Client;

use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function createItem(array $data): OrderItem
    {
        return OrderItem::create($data);
    }

    public function findOrderById(int $orderId, int $clientId)
    {
        return Order::where('id', $orderId)
            ->where('client_id', $clientId)
            ->with('items')
            ->first();
    }

    public function getOrdersByClient(int $clientId)
    {
        return Order::where('client_id', $clientId)
            ->with('items')
            ->orderByDesc('id')
            ->get();
    }

}
