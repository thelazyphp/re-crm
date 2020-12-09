<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UpdateTeamMember
{
    /**
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @param  \App\Models\User  $teamMember
     * @param  array  $input
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user, Team $team, User $teamMember, array &$input)
    {
        Gate::forUser($user)->authorize(
            'update-team-member', [$team, $teamMember]
        );

        $rules = [
            'profile_photo' => 'nullable|image|max:20480',
            'name' => 'string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'string|email|max:255|unique:users,email,'.$teamMember->id,
            'password' => 'string|min:8',
        ];

        Validator::make(
            $input,
            $rules,
            trans('api.errors.validation'))->validate();

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        DB::transaction(function () use ($teamMember, $input) {
            $teamMember->update($input);

            if (isset($input['profile_photo'])) {
                if (is_null($input['profile_photo'])) {
                    $teamMember->deleteProfilePhoto();
                } else {
                    $teamMember->updateProfilePhoto($input['profile_photo']);
                }
            }
        });
    }
}
