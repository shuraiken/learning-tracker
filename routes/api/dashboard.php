<?php

use App\Http\Controllers\Dashboard\DashboardActivityController;
use App\Http\Controllers\Dashboard\DashboardOverviewController;
use App\Http\Controllers\Dashboard\DashboardRecentSessionController;
use App\Http\Controllers\Dashboard\DashboardSkillBreakdownController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->group(function () {
        Route::get('overview', DashboardOverviewController::class);
        Route::get('skill-breakdown', DashboardSkillBreakdownController::class);
        Route::get('weekly-activity', DashboardActivityController::class);
        Route::get('recent-sessions', DashboardRecentSessionController::class);
    });
