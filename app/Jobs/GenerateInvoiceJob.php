<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;



class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {

        sleep(1);

        $invoice = Invoice::create([
            'order_id' => $this->order->id,
            'status'   => 'completed',
            'message'  => "Invoice created for order #{$this->order->id}"
        ]);

        $emailTo = $this->order->customer_email ?: 'no-reply@example.com';

        Mail::to($emailTo)->send(new InvoiceMail($this->order, $invoice));

        \Log::info("Invoice created for order {$this->order->id}");

    }
}
