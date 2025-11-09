<?php

namespace App\Repositories\Client;

use App\Models\Order;

class OrderRepository
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }
}
