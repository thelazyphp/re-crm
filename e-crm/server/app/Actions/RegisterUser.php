<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterUser
{
    /**
     * @param  array  $input
     * @return \App\Models\User
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(array &$input)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        Validator::make(
            $input,
            $rules,
            trans('api.errors.validation'))->validate();

        $input['password'] = Hash::make($input['password']);

        return tap(User::create($input), function (User $user) {
            if ($user instanceof MustVerifyEmail) {
                //
            }
        });
    }
}
