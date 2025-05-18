<?php

namespace App\Services\Auth;

use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class CompanyAuthService
{
    public function register(array $data): string
    {
        $company = Company::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $company->createToken('CompanyToken')->accessToken;
    }
}
