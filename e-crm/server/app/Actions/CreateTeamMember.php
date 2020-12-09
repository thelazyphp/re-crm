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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @param  array  $input
     * @return \App\Models\User
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(User $user, Team $team, array &$input)
    {
        Gate::forUser($user)->authorize('create-team-member', $team);

        $rules = [
            'profile_photo' => 'nullable|image|max:20480',
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];

        Validator::make(
            $input,
            $rules,
            trans('api.errors.validation'))->validate();

        $input['password'] = Hash::make($input['password']);

        return DB::transaction(function () use ($team, $input) {
            return tap(User::create($input), function (User $teamMember) use ($team, $input) {
                $team->members()->attach($teamMember);

                if (isset($input['profile_photo'])) {
                    if (! is_null($input['profile_photo'])) {
                        $teamMember->updateProfilePhoto($input['profile_photo']);
                    }
                }
            });
        });
    }
}
