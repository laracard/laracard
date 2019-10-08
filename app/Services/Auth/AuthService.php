<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Interfaces\Auth\AuthInterface;

class AuthService implements AuthInterface
{
    public function register(User $user, string $password): bool
    {
        $user->salt = $this->generateSalt();
        $user->password = Hash::make($password . $user->salt);
        $user->status = 1;
        return $user->save();
    }


    private function generateSalt()
    {
        return Str::random(16);
    }
}