<?php

use App\Events\GlobalMessage;
use App\Http\Controllers\Chat\SendMessage;
use Illuminate\Support\Facades\Route;


Route::post('/send-message', [SendMessage::class, 'create'])->name('send-message');
