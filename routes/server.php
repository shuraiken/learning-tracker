<?php

use App\Http\Controllers\Api\MasteryController as ApiMasteryController;
use App\Http\Controllers\Api\LearningController as ApiLearningController;
use Illuminate\Support\Facades\Route;

Route::prefix('server')->group(function () {
    Route::resource('learnings', ApiLearningController::class)->except(['create', 'edit']);
    Route::resource('masteries', ApiMasteryController::class)->except(['create', 'edit']);

    require __DIR__ . '/api/learning-sessions.php';
});
