<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\Client\ProjectController;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // ===== PROPERTI =====
    Route::get('/properti/karyawan', [PropertiController::class, 'karyawan'])
        ->name('properti.karyawan');

    Route::get('/properti/client', [PropertiController::class, 'client'])
        ->name('properti.client');

    Route::get('/properti/dokumen', [PropertiController::class, 'dokumen'])
        ->name('properti.dokumen');

    Route::get('/properti/fisik', [PropertiController::class, 'fisik'])
        ->name('properti.fisik');

    Route::get('/properti/penilaian', [PropertiController::class, 'penilaian'])
        ->name('properti.penilaian');
        // Edit project (show form)
        // Show project (view details)
        Route::get('/projects/{project}', [\App\Http\Controllers\Client\ProjectController::class, 'show'])
            ->name('projects.show');

        // Edit project (show form)
        Route::get('/projects/{project}/edit', [\App\Http\Controllers\Client\ProjectController::class, 'edit'])
            ->name('projects.edit');
        // Update project (PATCH/PUT)
        Route::match(['put', 'patch'], '/projects/{project}', [\App\Http\Controllers\Client\ProjectController::class, 'update'])
            ->name('projects.update');
    // ===== CLIENT PROJECT CRUD =====
    Route::prefix('client')->name('client.')->group(function () {

        Route::get('/projects/create', [ProjectController::class, 'create'])
            ->name('projects.create');

        Route::post('/projects', [ProjectController::class, 'store'])
            ->name('projects.store');


    });

    // ===== PROFILE =====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
