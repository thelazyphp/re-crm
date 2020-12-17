<?php

namespace App;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTeam
{
    /**
     * Get the team of the user.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
