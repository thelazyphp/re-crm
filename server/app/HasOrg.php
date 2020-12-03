<?php

namespace App;

use App\Models\User;

trait HasOrg
{
    /**
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function sameOrg(User $user)
    {
        return $this->id == $user->org->id;
    }
}
