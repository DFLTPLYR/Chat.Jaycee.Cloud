<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\UserHasProfilePhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, UserHasProfilePhoto;

    protected static function booted(): void
    {
        static::created(function ($user) {
            $user->preference()->create();
        });
    }

    // assignable collumns
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
    ];

    // hidden on load
    protected $hidden = [
        'password',
        'remember_token',
        'profile_photo',
    ];

    // UserHasProfilePhoto trait
    protected $appends = [
        'profile_photo_url',
    ];

    // eager load
    protected $with = ['preference'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }
}
