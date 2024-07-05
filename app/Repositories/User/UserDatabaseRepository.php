<?php

namespace App\Repositories\User;

use App\Contracts\User\UserRepository;
use App\Models\User;

class UserDatabaseRepository implements UserRepository
{
    /**
     * Create a new user
     *
     * @param User $user
     * @return void
     */
    public function create(array $data): User
    {
        return User::create($data);
    }
}