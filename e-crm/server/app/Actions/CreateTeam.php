<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateTeam
{
    /**
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return \App\Models\Team
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(User $user, array &$input)
    {
        $rules = [
            'name' => 'nullable|string|max:255',
        ];

        Validator::make(
            $input,
            $rules,
            trans('api.errors.validation'))->validate();

        return DB::transaction(function () use ($user, $input) {
            return tap(new Team([
                'name' => $input['name'],
            ]), function (Team $team) use ($user) {
                $team->owner()->associate($user)->save();
                $user->teams()->attach($team);
                $user->currentTeam()->associate($team)->save();
            });
        });
    }
}
