<?php

namespace App\Services\Client;

use App\Models\Order;
use App\Models\Invoice;
use App\Repositories\Client\InvoiceRepository;

class InvoiceService
{
    protected InvoiceRepository $repository;

    public function __construct(InvoiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generate(Order $order): Invoice
    {
        return $this->repository->create([
            'order_id' => $order->id,
            'status'   => 'completed',
            'message'  => "Invoice generated for order #{$order->id}",
        ]);
    }
}
