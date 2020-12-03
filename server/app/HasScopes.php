<?php

namespace App;

trait HasScopes
{
    /**
     * @param  string  $action
     * @return bool
     */
    public function scopesAllows($action)
    {
        return in_array($action, $this->scopes);
    }
}
