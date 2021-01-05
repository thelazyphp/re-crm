<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'org_id',
        'inviter_id',
        'email',
        'token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];

    public function org()
    {
        return $this->belongsTo(Org::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }
}
