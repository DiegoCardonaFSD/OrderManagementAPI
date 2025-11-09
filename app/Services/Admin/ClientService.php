<?php

namespace App\Services\Admin;

use App\Repositories\Admin\ClientRepository;
use App\Models\Client;

class ClientService
{
    protected ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createClient(array $data): Client
    {
        return $this->repository->create($data);
    }
}
