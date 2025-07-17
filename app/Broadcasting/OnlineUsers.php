<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OnlineUsers
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user): array|bool
    {
        if (Auth::check()) {
            return [
                'id' => $user->id,
                'user' => $user->name,
                'profile_picture' => $user->profile_photo_url];
        }
    }
}
