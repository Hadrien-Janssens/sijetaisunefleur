<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('caisse', function () {
    return Inertia::render('Caisse');
})->middleware(['auth', 'verified'])->name('caisse');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
