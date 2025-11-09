<?php

namespace App\Repositories\Client;

use App\Models\User;

class UserRepository
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByEmailAndClient(string $email, int $clientId): ?User
    {
        return $this->model
            ->where('email', $email)
            ->where('client_id', $clientId)
            ->first();
    }
}
