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
}
