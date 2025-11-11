<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public Invoice $invoice;

    public function __construct(Order $order, Invoice $invoice)
    {
        $this->order = $order;
        $this->invoice = $invoice;
    }

    public function build()
    {
        $html = view('emails.invoice_html', [
            'order'   => $this->order,
            'invoice' => $this->invoice,
        ])->render();

        return $this->subject("Invoice for Order #{$this->order->id}")
                    ->html($html)                     
                    ->text('emails.invoice_text');
    }

}



