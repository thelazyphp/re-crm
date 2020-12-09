<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UpdateTeam
{
    /**
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @param  array  $input
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user, Team $team, array &$input)
    {
        Gate::forUser($user)->authorize('update-team', $team);

        $rules = [
            'name' => 'string|max:255',
        ];

        Validator::make(
            $input,
            $rules,
            trans('api.errors.validation'))->validate();

        $team->update($input);
    }
}
