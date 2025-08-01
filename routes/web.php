<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Broadcast::routes();

require __DIR__.'/chat.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
