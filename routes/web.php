<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;


// Public routes
Route::get('/', [EventController::class, 'publicIndex'])->name('home');
Route::get('/events', [EventController::class, 'publicIndex'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/buy', [App\Http\Controllers\PublicOrderController::class, 'store'])->name('events.buy');

// Admin event routes
Route::prefix('admin')->name('admin.events.')->group(function () {
    Route::post('/events/bulk-delete', [EventController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('/events/{event}/clone', [EventController::class, 'clone'])->name('clone');
    Route::get('/events/export', [EventController::class, 'export'])->name('export');
    Route::get('/events', [EventController::class, 'index'])->name('index');
    Route::get('/events/create', [EventController::class, 'create'])->name('create');
    Route::post('/events', [EventController::class, 'store'])->name('store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('destroy');
});

// Admin lokasi routes
Route::prefix('admin')->name('admin.lokasi.')->group(function () {
    Route::post('/lokasi/bulk-delete', [\App\Http\Controllers\LokasiController::class, 'bulkDelete'])->name('bulkDelete');
    Route::get('/lokasi', [\App\Http\Controllers\LokasiController::class, 'index'])->name('index');
    Route::get('/lokasi/create', [\App\Http\Controllers\LokasiController::class, 'create'])->name('create');
    Route::post('/lokasi', [\App\Http\Controllers\LokasiController::class, 'store'])->name('store');
    Route::get('/lokasi/{lokasi}/edit', [\App\Http\Controllers\LokasiController::class, 'edit'])->name('edit');
    Route::put('/lokasi/{lokasi}', [\App\Http\Controllers\LokasiController::class, 'update'])->name('update');
    Route::delete('/lokasi/{lokasi}', [\App\Http\Controllers\LokasiController::class, 'destroy'])->name('destroy');
});
