<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
    ];

    public function owner()
    {
        return $this->belongsTo(
            User::class, 'owner_id'
        );
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->as('membership')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
