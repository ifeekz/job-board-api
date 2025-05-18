<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\CompanyRegisterRequest;
use App\Http\Requests\Auth\CompanyLoginRequest;
use App\Services\Auth\CompanyAuthService;

class CompanyAuthController extends Controller
{
    protected CompanyAuthService $authService;

    public function __construct(CompanyAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(CompanyRegisterRequest $request)
    {
        $token = $this->authService->register($request->validated());

        return ApiResponse::success([
            'token' => $token,
        ], 'Company registered successfully', 201);
    }

    public function login(CompanyLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = $this->authService->login($credentials);

        if (!$token) {
            return ApiResponse::error('Invalid credentials', null, 401);
        }

        return ApiResponse::success([
            'token' => $token,
        ], 'Login successful');
    }
}
