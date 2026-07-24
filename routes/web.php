<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearningController;

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
     require __DIR__ . '/server.php';
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
