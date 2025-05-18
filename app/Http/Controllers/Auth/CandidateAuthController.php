<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\CandidateRegisterRequest;
use App\Http\Requests\Auth\CandidateLoginRequest;
use App\Services\Auth\CandidateAuthService;

class CandidateAuthController extends Controller
{
    protected CandidateAuthService $authService;

    public function __construct(CandidateAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(CandidateRegisterRequest $request)
    {
        $token = $this->authService->register($request->validated());

        return ApiResponse::success([
            'token' => $token,
        ], 'Candidate registered successfully', 201);
    }

    public function login(CandidateLoginRequest $request)
    {
        $token = $this->authService->login($request->validated());

        if (!$token) {
            return ApiResponse::error('Invalid credentials', null, 401);
        }

        return ApiResponse::success([
            'token' => $token,
        ], 'Login successful');
    }
}
