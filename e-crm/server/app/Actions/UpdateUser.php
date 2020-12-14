<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateUser
{
    /**
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(User $user, array &$input)
    {
        Validator::make($input, [
            'profile_cover' => ['nullable', 'image', 'max:20480'],
            'profile_photo' => ['nullable', 'image', 'max:20480'],
            'name' => ['string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['string', 'min:8'],
        ])->validate();

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        DB::transaction(function () use ($user, $input) {
            tap($user->update($input), function (User $user) use ($input) {
                if (isset($input['profile_cover'])) {
                    if (is_null($input['profile_cover'])) {
                        $user->deleteProfileCover();
                    } else {
                        $user->updateProfileCover($input['profile_cover']);
                    }
                }

                if (isset($input['profile_photo'])) {
                    if (is_null($input['profile_photo'])) {
                        $user->deleteProfilePhoto();
                    } else {
                        $user->updateProfilePhoto($input['profile_photo']);
                    }
                }
            });
        });
    }
}
