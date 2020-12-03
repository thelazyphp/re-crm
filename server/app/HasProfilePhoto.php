<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait HasProfilePhoto
{
    /**
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ?? Storage::disk('public')->url($this->profile_photo_path);
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
        tap($this->profile_photo_path, function ($cur) use ($photo) {
            if ($cur) {
                Storage::disk('public')->delete($cur);
            }

            $this->forceFill([
                'profile_photo_path' => $photo->storePublicly('profile-photos', 'public'),
            ])->save();
        });
    }
}
