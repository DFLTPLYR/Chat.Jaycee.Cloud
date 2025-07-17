<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{

    protected $fillable = [
        'text_size',
        'notifications'
    ];

    protected $casts = [
        'text_size' => \App\Enums\TextSize::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
