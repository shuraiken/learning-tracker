<?php

use App\Http\Controllers\ActivateLearningSessionAndLogController;
use App\Http\Controllers\ActivateLearningSessionController;
use App\Http\Controllers\ActiveLearningSessionController;
use App\Http\Controllers\Api\LearningSessionController;
use App\Http\Controllers\PauseLearningSessionController;
use App\Http\Controllers\ResumeLearningSessionController;
use App\Http\Controllers\EndLearningSessionController;
use App\Http\Controllers\StopLearningSessionController; 
use App\Http\Controllers\StartLearningSessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('learning-sessions')->group(function () {
    Route::get('/active', [ActiveLearningSessionController::class, 'index']);
    Route::post('', [LearningSessionController::class, 'store']);
    Route::post('/activate-session-and-log', [ActivateLearningSessionAndLogController::class, 'store']);
    Route::post('/{id}/activate', [ActivateLearningSessionController::class, 'store']);
    Route::post('/{id}/pause', [PauseLearningSessionController::class, 'store']);
    Route::post('/{id}/resume', [ResumeLearningSessionController::class, 'store']);
    Route::post('/{id}/end', [EndLearningSessionController::class, 'store']);

    Route::post('/{id}/start-log', [StartLearningSessionController::class, 'store']);
    Route::post('/{id}/stop-log', [StopLearningSessionController::class, 'store']);
});
