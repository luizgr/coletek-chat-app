<?php

namespace App\Contracts\User;

use App\Models\User;

interface UserRepository
{
    /**
     * Create a new user
     *
     * @param User $user
     * @return void
     */
    public function create(array $data): User;
}