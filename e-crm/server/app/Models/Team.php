<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the team owner.
     */
    public function owner()
    {
        return $this->belongsTo(
            User::class, 'owner_id'
        );
    }

    /**
     * Get the team members.
     */
    public function members()
    {
        return $this->hasMany(User::class);
    }
}
