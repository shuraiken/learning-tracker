<?php

use Inertia\Inertia;
use App\Models\Learning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasteryController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\LearningSessionLogController;
use App\Http\Controllers\Api\LearningSessionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\MasteryController as ApiMasteryController;
use App\Http\Controllers\Api\LearningController as ApiLearningController;

Route::get('/', function (Request $request) {
    return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    /**
     * ==============
     * PAGES
     * ==============
     */
    Route::resource('learnings', LearningController::class);

    /**
     * ==============
     * API
     * ==============
     */
    Route::prefix('server')->group(function () {
        Route::resource('learnings', ApiLearningController::class)->except(['create', 'edit']);
        Route::resource('masteries', ApiMasteryController::class)->except(['create', 'edit']);

        Route::prefix('learning-sessions')->group(function () {
            Route::post('', [LearningSessionController::class, 'store']);
            Route::post('/{id}/run', [LearningSessionController::class, 'updateRunning']);
        });
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
