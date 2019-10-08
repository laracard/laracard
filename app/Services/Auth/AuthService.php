<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Interfaces\Auth\AuthInterface;

class AuthService implements AuthInterface
{
    public function register(User $user, string $password): bool
    {
        $user->salt = $this->generateSalt();
        Log::error($password . $user->salt);
        $user->password = Hash::make($password);
        $user->status = 1;
        return $user->save();
    }

    public function check(User $user, string $password): bool
    {
        return Auth::attempt(['email' => $user->email, 'password' => $password, 'status' => 1]);
//
//        var_dump(Hash::needsRehash($user->passowrd));
//        Log::error($password . $user->salt . '   ' . $user->password);
//        $res = Hash::check($password, $user->passowrd);
//        var_dump($res);
//        die;
//        return $res;
    }

    private function generateSalt()
    {
        return Str::random(16);
    }
}