<?php

namespace App\Services\Auth;

use App\Contracts\User\UserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
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
}