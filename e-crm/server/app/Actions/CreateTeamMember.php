<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateTeamMember
{
    /**
     * @param  \App\Models\Team  $team
     * @param  array  $input
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Team $team, array &$input)
    {
        Gate::authorize('team-member:create');

        Validator::make($input, [
            'profile_photo' => ['nullable', 'image', 'max:20480'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ], __('api.errors.validation'))->validate();

        return DB::transaction(function () use ($team, $input) {
            $teamMember = User::create([
                'name' => $input['name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $team->members()->save($teamMember);

            if (
                isset($input['profile_photo'])
                && !is_null($input['profile_photo'])
            ) {
                $teamMember->updateProfilePhoto($input['profile_photo']);
            }

            return $teamMember;
        });
    }
}
