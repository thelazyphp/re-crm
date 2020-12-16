<?php

namespace App;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAddress
{
    /**
     * Get the model address.
     */
    public function address(): MorphOne
    {
        return $this->morphOne(
            Address::class, 'addressable'
        );
    }
}
