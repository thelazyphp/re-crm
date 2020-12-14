<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfileCover
{
    /**
     * @return string|null
     */
    public function getProfileCoverUrlAttribute()
    {
        return $this->profile_cover_path ? Storage::disk('public')->url($this->profile_cover_path) : null;
    }

    /**
     * @return void
     */
    public function deleteProfileCover()
    {
        Storage::disk('public')->delete($this->profile_cover_path);

        $this->forceFill([
            'profile_cover_path' => null,
        ])->save();
    }

    /**
     * @param  \Illuminate\Http\UploadedFile  $cover
     * @return void
     */
    public function updateProfileCover(UploadedFile $cover)
    {
        return tap($this->profile_cover_path, function ($previous) use ($cover) {
            if ($previous) {
                Storage::disk('public')->delete($previous);
            }

            $this->forceFill([
                'profile_cover_path' => $cover->storePublicly('profile-covers', [
                    'disk' => 'public',
                ]),
            ])->save();
        });
    }
}
