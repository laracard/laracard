<?php

namespace App\Interfaces\Auth;

use App\Models\User;

interface  AuthInterface
{
    public function register(User $user, string $password): bool;
}