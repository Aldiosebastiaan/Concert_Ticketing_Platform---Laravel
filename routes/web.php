<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [EventController::class, 'publicIndex'])->name('home');
Route::get('/events', [EventController::class, 'publicIndex'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Admin event routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', EventController::class);
});
