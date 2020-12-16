<?php

namespace App;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTeam
{
    /**
     * Get the user team.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return bool
     */
    public function isTeamOwner()
    {
        return $this->id == $this->team->owner->id;
    }

    /**
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function hasTeamMember(User $user)
    {
        return $this->team->members->contains(function (User $member) use ($user) {
            return $user->id == $member->id;
        });
    }
}
