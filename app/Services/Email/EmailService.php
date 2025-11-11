<?php

namespace App\Services\Email;

use App\Models\Order;
use App\Models\Invoice;
use App\Mail\InvoiceMail;
use App\Repositories\Email\EmailSenderRepository;

class EmailService
{
    protected EmailSenderRepository $mailer;

    public function __construct(EmailSenderRepository $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendInvoiceEmail(Order $order, Invoice $invoice): void
    {
        $to = $order->customer_email ?: 'no-reply@example.com';

        $this->mailer->send($to, new InvoiceMail($order, $invoice));
    }
}
