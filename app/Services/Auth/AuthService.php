<?php

namespace App\Services\Auth;

use App\Exceptions\LoginFailedException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Get the authenticated user
     *
     * @return \App\Models\User
     */
    public function user()
    {
        return Auth::user();
    }
    
    /**
     * Login a user
     *
     * @param array $data
     * @return string
     * @throws LoginFailedException
     */
    public function login(array $data)
    {
        if (!Auth::attempt($data)) {
            throw new LoginFailedException();
        }

        $user = Auth::user();

        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Logout a user
     *
     * @return void
     */
    public function logout()
    {
        $currentToken = Auth::user()->currentAccessToken();

        if ($currentToken) $currentToken->delete();
    }
}