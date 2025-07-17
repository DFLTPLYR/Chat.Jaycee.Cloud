<?php

namespace App\Models;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'reply_to_id',
        'message',
    ];

    protected $casts = [
        'message' => Encrypted::class,
    ];
}
