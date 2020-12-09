<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DeleteTeam
{
    /**
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(User $user, Team $team)
    {
        Gate::forUser($user)->authorize('delete-team', $team);

        DB::transaction(function () use ($team) {
            $team->members()->each(function (User $teamMember) use ($team) {
                if ($teamMember->isCurrentTeam($team)) {
                    $teamMember->currentTeam()->dissociate()->save();
                }
            });

            $team->members()->detach();
            $team->delete();
        });
    }
}
