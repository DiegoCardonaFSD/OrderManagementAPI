<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AdminRepository;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    protected AdminRepository $repository;

    public function __construct(AdminRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Attempts to login the admin. Returns array with keys:
     *  - success: bool
     *  - message: string (on failure)
     *  - token: string (on success)
     *  - admin: Admin model (on success)
     */
    public function login(string $email, string $password): array
    {
        $admin = $this->repository->findByEmail($email);

        if (! $admin || ! Hash::check($password, $admin->password)) {
            return [
                'success' => false,
                'message' => __('api.auth.invalid_credentials'),
            ];
        }

        // Create token with admin scopes (abilities)
        $token = $admin->createToken('admin-token', ['admin.full_access']);

        return [
            'success' => true,
            'token' => $token->plainTextToken,
            'admin' => $admin,
        ];
    }
}
