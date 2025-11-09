<?php

namespace App\Repositories\Admin;

use App\Models\Client;

class ClientRepository
{
    protected Client $model;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Client
    {
        return $this->model->create($data);
    }
}
