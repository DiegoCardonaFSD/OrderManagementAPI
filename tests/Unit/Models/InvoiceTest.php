<?php

namespace Tests\Unit\Models;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_an_order()
    {
        $client = Client::factory()->create();
        $user   = User::factory()->create(['client_id' => $client->id]);

        $order = Order::factory()->create([
            'client_id' => $client->id,
            'user_id'   => $user->id,
        ]);

        $invoice = Invoice::factory()->create([
            'order_id' => $order->id,
            'status'   => 'completed',
        ]);

        $this->assertInstanceOf(Order::class, $invoice->order);
        $this->assertEquals($order->id, $invoice->order->id);
    }

}
