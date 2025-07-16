<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UserHasProfilePhoto
{
    public function hasProfilePhoto(): bool
    {
        return !empty($this->profile_photo) && Storage::disk('r2')->exists($this->profile_photo);
    }

    public function getProfilePhotoUrlAttribute(): string|null
    {
        return $this->hasProfilePhoto()
            ? Storage::disk('r2')->url($this->profile_photo)
            : asset('images/default-profile.png');
    }
}
