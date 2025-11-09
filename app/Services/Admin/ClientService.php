<?php

namespace App\Services\Admin;

use App\Repositories\Admin\ClientRepository;
use App\Repositories\Client\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;

class ClientService
{
    protected ClientRepository $clientRepository;
    protected UserRepository $userRepository;

    public function __construct(ClientRepository $clientRepository, UserRepository $userRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }

    public function createClient(array $data): Client
    {
        $client = $this->clientRepository->create($data);

        // automatic tenant user creation using relationship
        $password = Str::random(10);
        $client->users()->create([
            'name' => $client->name . ' Admin',
            'email' => $data['email'],
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        return $client;
    }

    public function listClients(array $filters, int $perPage = 0)
    {
        return $this->clientRepository->getAll($filters, $perPage);
    }

    public function getClientById(int $id): Client
    {
        return $this->clientRepository->findById($id);
    }

    public function updateClient(int $id, array $data): Client
    {
        return $this->clientRepository->update($id, $data);
    }

    public function deleteClient(int $id): bool
    {
        return $this->clientRepository->delete($id);
    }
}
