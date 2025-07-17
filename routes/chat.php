<?php

use App\Http\Controllers\Chat\SendMessage;
use App\Models\Message;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('chat', function () {
    return Inertia::render('Chats',
        [
            'latestMessages' => Inertia::always(fn () => Message::latest()->get()),
        ]);
})->middleware(['auth', 'verified'])->name('chat');

Route::post('/send-message', [SendMessage::class, 'create'])
    ->middleware(['sanitize'])
    ->name('send-message');
