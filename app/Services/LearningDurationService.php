<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\LearningSessionLogType;
use Illuminate\Support\Collection;

class LearningDurationService
{
    public function buildActiveIntervals(iterable $sessionLogs): array
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

    public function calculateDuration(array $intervals, string $periodStart, string $periodEnd): int
    {
        $totalDuration = 0;
        $parsedPeriodStart = Carbon::parse($periodStart);
        $parsedPeriodEnd = Carbon::parse($periodEnd);

        foreach ($intervals as $interval) {
            $intervalStart = Carbon::parse($interval['start']);
            $intervalEnd = Carbon::parse($interval['end']);

            $overlapStart = $intervalStart->max($parsedPeriodStart);
            $overlapEnd = $intervalEnd->min($parsedPeriodEnd);

            if ($overlapStart < $overlapEnd) {
                $totalDuration += $overlapStart->diffInSeconds($overlapEnd);
            }
        }

        return $totalDuration;
    }
}
