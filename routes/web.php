<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\Client\ProjectController;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/properti/karyawan', [PropertiController::class, 'karyawan'])->name('properti.karyawan');
    Route::get('/properti/client', [PropertiController::class, 'client'])->name('properti.client');
    Route::get('/properti/dokumen', [PropertiController::class, 'dokumen'])->name('properti.dokumen');
    Route::get('/properti/fisik', [PropertiController::class, 'fisik'])->name('properti.fisik');
    Route::get('/properti/penilaian', [PropertiController::class, 'penilaian'])->name('properti.penilaian');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/client/projects', [ProjectController::class, 'store'])
    ->name('client.projects.store');

});

require __DIR__.'/auth.php';
