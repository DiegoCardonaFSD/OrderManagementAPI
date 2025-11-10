<?php

namespace App\Services\Client;

use App\Repositories\Client\OrderRepository;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected OrderRepository $orders;

    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;
    }

    public function storeOrder(int $clientId, int $userId, array $items): Order
    {
        return DB::transaction(function () use ($clientId, $userId, $items) {

            $order = $this->orders->createOrder([
                'client_id' => $clientId,
                'user_id'   => $userId,
                'status'    => 'created',
                'total'     => 0,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;

                $this->orders->createItem([
                    'order_id' => $order->id,
                    'name'     => $item['name'],
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total' => $total]);

            return $order->fresh(['items']);
        });
    }

    public function getOrder(int $orderId, int $clientId): ?Order
    {
        return $this->orders->findOrderById($orderId, $clientId);
    }

    public function getOrdersForClient(int $clientId)
    {
        return $this->orders->getOrdersByClient($clientId);
    }


}
