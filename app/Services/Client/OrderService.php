<?php

namespace App\Services\Client;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Client\OrderRepository;
use App\Jobs\GenerateInvoiceJob;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected OrderRepository $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createOrder($user, array $items): Order
    {
        return DB::transaction(function () use ($user, $items) {
            $order = $this->repository->createOrder([
                'client_id' => $user->client_id,
                'user_id' => $user->id,
                'status' => 'created',
                'total' => collect($items)->sum(fn($i) => $i['quantity'] * $i['price']),
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            
            //GenerateInvoiceJob::dispatch($order);

            return $order;
        });
    }
}
