<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\Learning;
use App\Models\LearningSession;
use App\Models\LearningSessionLog;

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
        $periodEnd = today()->addDay()->startOfDay();

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

    public function getSkillBreakdown(): array
    {
        // $learnings = Learning::orderBy(
        //     LearningSession::select('total_duration')->sum('total_duration');
        // )->get();

        $learnings = Learning::where('user_id', $this->user->id)
            ->whereHas('learningSessions')
            ->withSum('learningSessions', 'total_duration')
            ->orderByDesc('learning_sessions_sum_total_duration')
            ->get()
            ->map(fn($learning) => [
                'id' => $learning->id,
                'name' => $learning->name,
                'total_duration' => (int) $learning->learning_sessions_sum_total_duration,
                'duration_formatted' => CarbonInterval::seconds(
                    $learning->learning_sessions_sum_total_duration
                )->cascade()->forHumans(),
            ]);

        return $learnings->values()->toArray();
    }

    public function getWeeklyActivity(): array
    {
        $startOfWeek = today()->startOfWeek();
        $endOfWeek = today()->endOfWeek()->addDay()->startOfDay();

        $logs = LearningSessionLog::whereHas('learningSession.learning', function ($query) {
                $query->where('user_id', $this->user->id);
            })
            ->whereBetween('occurred_at', [$startOfWeek, $endOfWeek])
            ->orderBy('occurred_at')
            ->get();

        $service = new LearningDurationService();
        $activeIntervals = $service->buildActiveIntervals($logs);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $result = [];

        foreach ($days as $index => $day) {
            $startOfDay = $startOfWeek->copy()->addDays($index);
            $endOfDay = $startOfDay->copy()->addDay();

            $result[$day] = $service->calculateDuration($activeIntervals, $startOfDay, $endOfDay);
        }

        return $result;
    }
}
