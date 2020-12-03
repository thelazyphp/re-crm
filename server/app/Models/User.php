<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\HasOrg;
use App\HasProfilePhoto;
use App\HasScopes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasOrg;
    use HasProfilePhoto;
    use HasScopes;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['profile_photo_url'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'owner' => false,
        'scopes' => [],
        'disabled' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'profile_photo_path',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'owner' => 'boolean',
        'scopes' => 'array',
        'disabled' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function org()
    {
        return $this->belongsTo('App\Models\Org');
    }

    /**
     * @return void
     */
    public function enable()
    {
        $this->forceFill([
            'disabled' => false,
        ])->save();
    }

    /**
     * @return void
     */
    public function disable()
    {
        $this->forceFill([
            'disabled' => true,
        ])->save();
    }

    /**
     * @return void
     */
    public function toggleDisabled()
    {
        $this->disabled ? $this->enable() : $this->disable();
    }
}
