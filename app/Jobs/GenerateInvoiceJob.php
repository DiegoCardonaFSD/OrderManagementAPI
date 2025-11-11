<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Client\InvoiceService;
use App\Services\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(InvoiceService $invoiceService, EmailService $emailService): void
    {
        // 1. Generar Invoice
        $invoice = $invoiceService->generate($this->order);

        // 2. Enviar Email
        $emailService->sendInvoiceEmail($this->order, $invoice);

        \Log::info("Invoice created & email sent for order {$this->order->id}");
    }
}
