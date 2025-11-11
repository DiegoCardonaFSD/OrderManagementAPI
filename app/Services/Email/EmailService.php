<?php

namespace App\Services\Email;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendInvoiceEmail(Order $order, Invoice $invoice): void
    {
        $emailTo = $order->customer_email ?: 'no-reply@example.com';

        Mail::to($emailTo)->send(
            new InvoiceMail($order, $invoice)
        );
    }
}
