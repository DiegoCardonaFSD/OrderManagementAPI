<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
</head>
<body>

<h2>Invoice for Order #{{ $order->id }}</h2>

<p><strong>Status:</strong> {{ $invoice->status }}</p>

<hr>

<h3>Customer Information</h3>

<ul>
    <li><strong>Name:</strong> {{ $order->customer_name }}</li>

    @if($order->customer_email)
        <li><strong>Email:</strong> {{ $order->customer_email }}</li>
    @endif

    @if($order->customer_phone)
        <li><strong>Phone:</strong> {{ $order->customer_phone }}</li>
    @endif

    @if($order->customer_address)
        <li><strong>Address:</strong> {{ $order->customer_address }}</li>
    @endif

    @if($order->customer_city)
        <li><strong>City:</strong> {{ $order->customer_city }}</li>
    @endif

    @if($order->customer_country)
        <li><strong>Country:</strong> {{ $order->customer_country }}</li>
    @endif

    @if($order->customer_tax_id)
        <li><strong>Tax ID:</strong> {{ $order->customer_tax_id }}</li>
    @endif

    @if($order->notes)
        <li><strong>Notes:</strong> {{ $order->notes }}</li>
    @endif
</ul>

<hr>

<h3>Items</h3>

<table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #f0f0f0;">
            <th align="left">Name</th>
            <th align="left">Quantity</th>
            <th align="left">Price</th>
            <th align="left">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>${{ number_format($item->subtotal, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3>Total: ${{ number_format($order->total, 2) }}</h3>

<hr>

<p>Thank you for your purchase.</p>

</body>
</html>
