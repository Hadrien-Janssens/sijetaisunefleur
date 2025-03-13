<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use Database\Seeders\TicketRowSeeder;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('login');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('caisse', function () {
        return Inertia::render('Caisse');
    })->name('caisse');

    Route::get('numbre', function () {
        return Inertia::render('Numbre');
    })->name('numbre.index');


    Route::get('invoice', function () {
        return Inertia::render('Invoice');
    })->name('invoice.index');

    Route::get('client', [ClientController::class, 'index'])->name('client.index');
    Route::get('client/{client}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('client', [ClientController::class, 'store'])->name('client.store');
    Route::put('client/{client}', [ClientController::class, 'update'])->name('client.update');

    Route::get('vente', [TicketController::class, 'index'])->name('vente.index');
    Route::post('ticket', [TicketController::class, 'store'])->name('ticket.store');

    Route::get('article', [CategoryController::class, 'index'])->name('article.index');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
