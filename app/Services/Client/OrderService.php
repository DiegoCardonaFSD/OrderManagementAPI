<?php

namespace App\Services\Client;

use App\Repositories\Client\OrderRepository;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Jobs\GenerateInvoiceJob;

class OrderService
{
    protected OrderRepository $orders;

    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;
    }

    public function storeOrder(int $clientId, int $userId, array $items, $customerData): Order
    {
        return DB::transaction(function () use ($clientId, $userId, $items, $customerData) {

            $order = $this->orders->createOrder([
                'client_id'        => $clientId,
                'user_id'          => $userId,
                'status'           => 'created',
                'total'            => 0,
                'customer_name'    => $customerData['customer_name'],
                'customer_email'   => $customerData['customer_email'] ?? null,
                'customer_phone'   => $customerData['customer_phone'] ?? null,
                'customer_address' => $customerData['customer_address'] ?? null,
                'customer_city'    => $customerData['customer_city'] ?? null,
                'customer_country' => $customerData['customer_country'] ?? null,
                'customer_tax_id'  => $customerData['customer_tax_id'] ?? null,
                'notes'            => $customerData['notes'] ?? null,
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

            GenerateInvoiceJob::dispatch($order);

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
