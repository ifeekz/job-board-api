<?php

namespace App\Services\Auth;

use App\Models\Candidate;
use Illuminate\Support\Facades\Hash;

class CandidateAuthService
{
    public function register(array $data): string
    {
        $Candidate = Candidate::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $Candidate->createToken('CandidateToken')->accessToken;
    }

    public function login(array $credentials): string|null
    {
        $Candidate = Candidate::where('email', $credentials['email'])->first();

        if (!$Candidate || !Hash::check($credentials['password'], $Candidate->password)) {
            return null;
        }

        return $Candidate->createToken('CandidateToken')->accessToken;
    }
}
