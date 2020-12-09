<?php

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DeleteTeamMember
{
    /**
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @param  \App\Models\User  $teamMember
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(User $user, Team $team, User $teamMember)
    {
        Gate::forUser($user)->authorize(
            'delete-team-member', [$team, $teamMember]
        );

        DB::transaction(function () use ($team, $teamMember) {
            $team->members()->detach($teamMember);

            if ($teamMember->isCurrentTeam($team)) {
                $teamMember->currentTeam()->dissociate()->save();
            }
        });
    }
}
