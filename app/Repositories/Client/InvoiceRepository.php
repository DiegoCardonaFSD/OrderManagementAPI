<?php

namespace App\Repositories\Client;

use App\Models\Invoice;

class InvoiceRepository
{
    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }
}
