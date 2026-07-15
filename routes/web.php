<?php

use Illuminate\Support\Facades\Route;

<<<<<<< Updated upstream
Route::get('/', function () {
    return view('welcome');
=======
// Public routes
Route::get('/', [EventController::class, 'publicIndex'])->name('home');
Route::get('/events', [EventController::class, 'publicIndex'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Admin event routes
Route::prefix('admin')->name('admin.events.')->middleware(['auth', 'verified'])->group(function () {
    Route::post('/events/bulk-delete', [EventController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('/events/{event}/clone', [EventController::class, 'clone'])->name('clone');
    Route::get('/events/export', [EventController::class, 'export'])->name('export');
    Route::get('/events', [EventController::class, 'index'])->name('index');
    Route::get('/events/create', [EventController::class, 'create'])->name('create');
    Route::post('/events', [EventController::class, 'store'])->name('store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('destroy');
>>>>>>> Stashed changes
});
