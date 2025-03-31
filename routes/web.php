<?php

use App\Http\Controllers\CaisseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use Database\Seeders\TicketRowSeeder;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    Mail::raw('Ceci est un test avec OVH', function ($message) {
        $message->to('hadrien.janssens7@gmail.com')
            ->subject('Test Laravel Mail avec OVH');
    });

    return 'Email envoyé !';
});

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('login');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('caisse', [CaisseController::class, 'index'])->name('caisse');

    Route::get('numbre', function () {
        return Inertia::render('Numbre');
    })->name('numbre.index');


    Route::get('invoice', function () {
        return Inertia::render('Invoice');
    })->name('invoice.index');

    Route::get('client', [ClientController::class, 'index'])->name('client.index');
    Route::get('client/{id}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('client', [ClientController::class, 'store'])->name('client.store');
    Route::put('client/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::delete('client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::delete('client/delete/{id}', [ClientController::class, 'forceDelete'])->name('client.forceDelete');
    Route::get('restore/client/{id}', [ClientController::class, 'restore'])->name('client.restore');
    Route::get('export/client', [ClientController::class, 'export'])->name('client.export');


    Route::get('vente', [TicketController::class, 'index'])->name('vente.index');
    Route::get('ticket/{id}', [TicketController::class, 'edit'])->name('ticket.edit');
    Route::post('ticket', [TicketController::class, 'store'])->name('ticket.store');
    Route::delete('ticket/{id}', [TicketController::class, 'destroy'])->name('ticket.destroy');
    Route::delete('ticket/delete/{id}', [TicketController::class, 'forceDelete'])->name('ticket.forceDelete');
    Route::get('restore/ticket/{id}', [TicketController::class, 'restore'])->name('ticket.restore');
    Route::post('sendmail/ticket/{id}', [TicketController::class, 'sendMail'])->name('ticket.sendMail');

    Route::get('article', [CategoryController::class, 'index'])->name('article.index');
    Route::post('article', [CategoryController::class, 'store'])->name('article.store');
    Route::delete('article/{category}', [CategoryController::class, 'destroy'])->name('article.destroy');

    Route::get('export/ticket', [TicketController::class, 'export'])->name('ticket.export');

    Route::get('/facture/{ticketId}', [TicketController::class, 'generatePDF'])->name('ticket.print');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';