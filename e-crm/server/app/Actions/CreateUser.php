<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser
{
    /**
     * @param  array  $input
     * @return \App\Models\User
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(array &$input)
    {
        Validator::make($input, [
            'profile_cover' => ['nullable', 'image', 'max:20480'],
            'profile_photo' => ['nullable', 'image', 'max:20480'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ])->validate();

        $input['password'] = Hash::make($input['password']);

        return DB::transaction(function () use ($input) {
            return tap(User::create($input), function (User $user) use ($input) {
                if (
                    isset($input['profile_cover'])
                    && ! is_null($input['profile_cover'])
                ) {
                    $user->updateProfileCover($input['profile_cover']);
                }

                if (
                    isset($input['profile_photo'])
                    && ! is_null($input['profile_photo'])
                ) {
                    $user->updateProfilePhoto($input['profile_photo']);
                }
            });
        });
    }
}
