<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\LearningSessionLogType;

class LearningDurationService
{
    public function buildActiveIntervals($sessionLogs)
    {
        $intervals = [];
        $currentStart = null;

        foreach ($sessionLogs as $log) {
            if ($log->type === LearningSessionLogType::START || $log->type === LearningSessionLogType::RESUME) {
                $currentStart = $log->occurred_at;
            } else if ($log->type === LearningSessionLogType::PAUSE || $log->type === LearningSessionLogType::STOP) {
                if ($currentStart !== null) {
                    $intervals[] = [
                        'start' => $currentStart,
                        'end' => $log->occurred_at
                    ];

                    $currentStart = null;
                }
            }
        }

        return $intervals;
    }

    public function calculateDuration($intervals, $periodStart, $periodEnd)
    {
        $totalDuration = 0;
        $parsedPeriodStart = Carbon::parse($periodStart);
        $parsedPeriodEnd = Carbon::parse($periodEnd);

        foreach ($intervals as $interval) {
            $intervalStart = Carbon::parse($interval['start']);
            $intervalend = Carbon::parse($interval['end']);

            $overlapStart = max($intervalStart, $parsedPeriodStart);
            $overlapEnd = min($intervalEnd, $parsedPeriodEnd);

            if ($overlapStart < $overlapEnd) {
                $totalDuration += $overlapStart->diffInSeconds($overlapEnd);
            }
        }

        return $totalDuration;
    }
}
