<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Exceptions\AccountExistException;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\PasswordInvalidException;

class UserService
{
    const PASSWORD_REGEX = "/^(?=.*[^a-zA-Z0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/";

    public function signUp(
        string $account,
        string $password,
        string $username
        )
    {
        $validator = Validator::make(['password' => $password], [
            'password' => 'regex:' . UserService::PASSWORD_REGEX
        ]);
        if ($validator->fails()) {
            throw new PasswordInvalidException();
        }
        $user = User::where('account', $account)->first();
        if($user !== null){
            throw new AccountExistException();
        }

        User::create([
            'account' => $account,
            'password' => Hash::make($password),
            'name' => Crypt::encryptString($username),
        ]);
        return true;
    }

}