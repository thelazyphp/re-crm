<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateNewUser
{
    /**
     * @param  array  $input
     * @return \App\Models\User
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(array &$input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], __('api.errors.validation'))->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $team = new Team();
            $team->owner()->associate($user);
            $team->save();

            $user->team()->associate($team);
            $user->save();

            return $user;
        });
    }
}
