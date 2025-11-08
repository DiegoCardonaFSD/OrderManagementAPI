<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Services\Admin\AdminAuthService;

class AdminAuthController extends Controller
{
    protected AdminAuthService $authService;

    public function __construct(AdminAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AdminLoginRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);

        if (! $result['success']) {
            return response()->json(['message' => $result['message']], 401);
        }

        return response()->json([
            'token' => $result['token'],
            'message' => __('api.admin.login_success'),
            'admin' => [
                'id' => $result['admin']->id,
                'name' => $result['admin']->name,
                'email' => $result['admin']->email,
            ],
        ]);
    }
}
