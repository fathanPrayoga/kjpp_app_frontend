<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\Client\ProjectController;
use App\Http\Controllers\ProjectDocumentController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // ===== PROPERTI =====
    Route::get('/properti/karyawan', [PropertiController::class, 'karyawan'])->name('properti.karyawan');
    Route::get('/properti/client', [PropertiController::class, 'client'])->name('properti.client');
    Route::get('/properti/dokumen', [PropertiController::class, 'dokumen'])->name('properti.dokumen');
    Route::get('/properti/fisik', [PropertiController::class, 'fisik'])->name('properti.fisik');
    Route::get('/properti/penilaian', [PropertiController::class, 'penilaian'])->name('properti.penilaian');

    // Karyawan documents (list + verify + download) - Fitur dari Yoga
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/documents', [ProjectDocumentController::class, 'index'])->name('documents');
        Route::get('/project-documents/{document}/download', [ProjectDocumentController::class, 'download'])->name('document.download');
        Route::post('/project-documents/{document}/verify', [ProjectDocumentController::class, 'verify'])->name('document.verify');
        Route::post('/projects/{project}/verify', [ProjectDocumentController::class, 'verifyProject'])->name('project.verify');
        Route::post('/verify-batch', [ProjectDocumentController::class, 'verifyBatch'])->name('verify-batch');
        Route::get('/projects/{project}/download-all', [ProjectDocumentController::class, 'downloadAll'])->name('project.download-all');
    });

    // Fitur Detail Project & Nilai - Fitur dari Yoga
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/properti/nilai/{projectId}', [PropertiController::class, 'getNilai'])->name('properti.nilai.get');
    Route::post('/properti/nilai/{projectId}', [PropertiController::class, 'saveNilai'])->name('properti.nilai.save');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::match(['put', 'patch'], '/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

    // ===== CLIENT PROJECT CRUD =====
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    });

    // ===== PROFILE =====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== CHAT =====
    Route::get('/chats', function () { return view('chats.index'); })->name('chats.index');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/messages/conversation/{user}', [\App\Http\Controllers\MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::match(['put', 'patch'], '/messages/{message}', [\App\Http\Controllers\MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/conversation/{user}/read', [\App\Http\Controllers\MessageController::class, 'markRead'])->name('messages.markRead');
});

// Laporan
Route::prefix('laporan')->middleware('auth')->group(function () {
    Route::get('/project', [PropertiController::class, 'laporanProject'])->name('laporan.project');
    Route::get('/project/{id}', [PropertiController::class, 'getProject'])->name('laporan.project.show');
    Route::post('/upload', [PropertiController::class, 'uploadLaporan'])->name('laporan.upload');
    Route::delete('/reset/{id}', [PropertiController::class, 'resetLaporan'])->name('laporan.reset');
    Route::get('/tahunan', [PropertiController::class, 'laporanTahunan'])->name('laporan.tahunan');
    Route::get('/tahunan/{year}', [PropertiController::class, 'getTahunanByYear'])->name('laporan.tahunan.show');
    Route::delete('/project/delete/{id}', [PropertiController::class, 'deleteProject'])->name('laporan.project.delete');
    Route::get('/tahunan/download-zip/{year}', [PropertiController::class, 'downloadZipTahunan'])->name('laporan.tahunan.zip');
});

require __DIR__.'/auth.php';