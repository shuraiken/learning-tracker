<?php

namespace App\Services;

use Carbon\CarbonInterval;
use App\Models\LearningSession;

class DashboardService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()?->user();
    }

    public function getOverviewData(): array
    {
        $totalLearnings = $this->user->learnings->count();

        return [
            'totalLearnings' => $totalLearnings,
            ...$this->getOverviewTimeSummary(),
        ];
    }

    public function getOverviewTimeSummary(): array
    {
        $totalTime = $this->user->learningSessions()->sum('total_duration');
        $timeFormatted = CarbonInterval::seconds($totalTime)->forHumans();
        // $averageTime = $totalTime / $totalLearnings;
        // $averageTimeFormatted = CarbonInterval::seconds($averageTime)->forHumans();

        return [
            'totalTime' => $totalTime,
            'today' => $this->getTimeToday()['totalTimeToday'],
            'thisWeek' => $this->getTimeThisWeek()['totalTimeThisWeek'],
            // 'totalLearningTime' => $timeFormatted,
            // 'today' => $this->getTimeToday()['timeFormatted'],
            // 'thisWeek' => $this->getTimeThisWeek()['timeFormatted'],
            // 'averageTime' => $averageTimeFormatted,
        ];
    }

    public function getTimeToday(): array
    {
        // $totalTimeToday = $this->user->learningSessions()->logs()->whereDate('learning_session_logs.created_at', today())->sum('total_duration');

        $periodStart = today();
        $periodEnd = today()->addDay();
        
        $totalTimeToday = $this->user->learningSessions()->whereDate('learning_sessions.created_at', today())->sum('total_duration');

        $timeFormatted = CarbonInterval::seconds($totalTimeToday)->forHumans();

        return [
            'totalTimeToday' => $totalTimeToday,
            'timeFormatted' => $timeFormatted,
        ];
    }

    public function getTimeThisWeek(): array
    {
        $periodStart = today()->startOfWeek();
        $periodEnd = today()->endOfWeek()->addDay()->startOfDay();
        
        $totalTimeThisWeek = $this->user->learningSessions()->whereDate('learning_sessions.created_at', '>=', today()->startOfWeek())->sum('total_duration');
        $timeFormatted = CarbonInterval::seconds($totalTimeThisWeek)->forHumans();

        return [
            'totalTimeThisWeek' => $totalTimeThisWeek,
            'timeFormatted' => $timeFormatted,
        ];
    }
}
