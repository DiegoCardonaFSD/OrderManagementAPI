<?php

namespace App\Services\Client;

use App\Models\Invoice;
use App\Models\Order;

class InvoiceService
{
    public function generate(Order $order): Invoice
    {
        return Invoice::create([
            'order_id' => $order->id,
            'status'   => 'completed',
            'message'  => "Invoice generated for order #{$order->id}",
        ]);
    }
}
