<?php

namespace App\Services\Client;

use App\Repositories\Client\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ClientAuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials, int $tenantId): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || $user->client_id != $tenantId || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('api.client.invalid_credentials')
            ]);
        }

        $token = $user->createToken('client-token', ['client.full_access']);

        return [
            'success' => true,
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }
}
