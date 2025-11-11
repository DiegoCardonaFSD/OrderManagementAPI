Invoice for Order #{{ $order->id }}

Status: {{ $invoice->status }}

Customer Information:
Name: {{ $order->customer_name }}
Email: {{ $order->customer_email }}
Phone: {{ $order->customer_phone }}
Address: {{ $order->customer_address }}
City: {{ $order->customer_city }}
Country: {{ $order->customer_country }}
Tax ID: {{ $order->customer_tax_id }}

Items:
@foreach ($order->items as $item)
- {{ $item->name }} ({{ $item->quantity }} × {{ number_format($item->price, 2) }}) = ${{ number_format($item->subtotal, 2) }}
@endforeach

Total: ${{ number_format($order->total, 2) }}

Thank you for your purchase.
