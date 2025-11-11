<?php

namespace Tests\Unit\Jobs;

use App\Jobs\GenerateInvoiceJob;
use App\Mail\InvoiceMail;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GenerateInvoiceJobTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_invoice_and_sends_email()
    {
        Mail::fake();

        $client = Client::factory()->create();
        $user   = User::factory()->create(['client_id' => $client->id]);

        $order = Order::factory()->create([
            'client_id' => $client->id,
            'user_id'   => $user->id,
            'customer_email' => 'john@example.com',
        ]);

        $invoiceService = app(\App\Services\Client\InvoiceService::class);
        $emailService = app(\App\Services\Email\EmailService::class);

        $job = new GenerateInvoiceJob($order);
        $job->handle($invoiceService, $emailService);

        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'status' => 'completed',
        ]);

        $invoice = Invoice::where('order_id', $order->id)->first();
        $this->assertNotNull($invoice);

        Mail::assertSent(InvoiceMail::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;
        });
    }
}
