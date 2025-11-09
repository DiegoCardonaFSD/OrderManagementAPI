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

    public function listClients(array $filters = [], int $perPage = 10)
    {
        return $this->repository->getAll($filters, $perPage);
    }

    public function getClientById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function updateClient(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteClient(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
