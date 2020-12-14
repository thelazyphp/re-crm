<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    /**
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? Storage::disk('public')->url($this->profile_photo_path) : null;
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
        return tap($this->profile_photo_path, function ($previous) use ($photo) {
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
