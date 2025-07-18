<?php

use App\Http\Controllers\Chat\SendMessage;
use App\Models\Message;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('chat', function (Request $request) {
    return Inertia::render('Chats', [
        'latestMessages' => Inertia::defer(
            fn () => Message::latest()
                ->take(25)
                ->get()
        ),
    ]);
})->middleware(['auth', 'verified'])->name('chat');

Route::post('/send-message', [SendMessage::class, 'create'])
    ->middleware(['sanitize'])
    ->name('send-message');
