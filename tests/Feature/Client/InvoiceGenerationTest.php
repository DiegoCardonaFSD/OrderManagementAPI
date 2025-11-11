<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Queue;
use App\Jobs\GenerateInvoiceJob;
use App\Services\Client\InvoiceService;
use App\Services\Email\EmailService;

class InvoiceGenerationTest extends TestCase
{
    use RefreshDatabase;

    private function customerPayload(): array
    {
        return [
            'customer_name'    => 'John Doe',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '+1 555 1234',
            'customer_address' => '123 Street',
            'customer_city'    => 'New York',
            'customer_country' => 'USA',
            'customer_tax_id'  => '123456789',
            'notes'            => 'Test note',
        ];
    }

    #[Test]
    public function it_creates_invoice_after_order_creation()
    {
        Queue::fake(); 

        $client = Client::factory()->create();
        $user   = User::factory()->create(['client_id' => $client->id]);

        $token = $user->createToken('user-token', ['client.full_access'])->plainTextToken;

        $payload = array_merge($this->customerPayload(), [
            'items' => [
                ['name' => 'Test Item', 'quantity' => 1, 'price' => 50]
            ]
        ]);

        $response = $this->json('POST', '/api/orders', $payload, [
            'Authorization' => "Bearer $token",
            'X-Tenant-ID'   => $client->id,
        ]);

        $response->assertStatus(201);

        $orderId = $response->json('data.id');
        $order = \App\Models\Order::find($orderId);

        Queue::assertPushed(GenerateInvoiceJob::class);

        $job = new GenerateInvoiceJob($order);
        $job->handle(
            app(InvoiceService::class),
            app(EmailService::class)
        );

        $this->assertDatabaseHas('invoices', [
            'order_id' => $orderId,
            'status'   => 'completed'
        ]);
    }

}
