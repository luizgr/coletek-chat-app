<?php

namespace App\Services;

use App\Exceptions\LoginFailedException;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     *
     * @param array $data
     * @return string
     */
    public function register(array $data)
    {
        $user = $this->userRepository->create([
            'name'      => $data['name'],
            'nickname'  => $data['nickname'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password'])
        ]);

        return $user->createToken('auth_token')->plainTextToken;
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

        $user = $this->userRepository->findByEmail($data['email']);

        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Logout a user
     *
     * @return void
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
    }
}