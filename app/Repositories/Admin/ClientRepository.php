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

    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', $filters['email']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id); 
    }

    public function update(int $id, array $data)
    {
        $client = $this->findById($id);
        $client->update($data);
        return $client;
    }

    public function delete(int $id): bool
    {
        $client = $this->findById($id);
        return $client->delete(); 
    }

}
