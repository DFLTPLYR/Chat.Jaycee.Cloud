<?php

use App\Broadcasting\OnlineUsers;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('OnlineUsers', OnlineUsers::class);
