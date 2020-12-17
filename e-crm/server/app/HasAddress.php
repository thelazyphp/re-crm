<?php

namespace App;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAddress
{
    /**
     * Get the address of the model.
     */
    public function address(): MorphOne
    {
        return $this->morphOne(
            Address::class, 'addressable'
        );
    }
}
