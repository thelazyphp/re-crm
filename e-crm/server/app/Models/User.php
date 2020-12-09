<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'email_verified',
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
        'password',
        'remember_token',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? Storage::disk('public')->url($this->profile_photo_path) : null;
    }

    /**
     * @return bool
     */
    public function getEmailVerifiedAttribute()
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Get the user's teams.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get the user's current team.
     */
    public function currentTeam()
    {
        return $this->belongsTo(
            Team::class, 'current_team_id'
        );
    }

    /**
     * @param  \App\Models\Team  $team
     * @return bool
     */
    public function belongsToTeam(Team $team)
    {
        return $this->teams->contains($team);
    }

    /**
     * @param  \App\Models\Team  $team
     * @return bool
     */
    public function ownsTeam(Team $team)
    {
        return $this->id == $team->owner->id;
    }

    /**
     * @param  \App\Models\Team  $team
     * @return bool
     */
    public function isCurrentTeam(Team $team)
    {
        $this->currentTeam->id == $team->id;
    }

    /**
     * @return void
     */
    public function deleteProfilePhoto()
    {
        Storage::disk('public')->delete($this->profile_photo_path);

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            if ($previous) {
                Storage::disk('public')->delete($previous);
            }

            $this->forceFill([
                'profile_photo_path' => $photo->storePublicly('profile-photos', [
                    'disk' => 'public',
                ]),
            ])->save();
        });
    }
}
