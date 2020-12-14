<?php

namespace App;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTeam
{
    /**
     * Get user's team.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
