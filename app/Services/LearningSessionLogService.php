<?php

namespace App\Services;

use App\Models\LearningSession;
use App\Models\LearningSessionLog;
use App\Enums\LearningSessionStatus;
use App\Enums\LearningSessionLogType;

class LearningSessionLogService
{
    public function __construct()
    {
    }

    public function findLearningSessionLog(int $id)
    {
        $log = LearningSessionLog::where('id', $id)->first();

        if (!$log) {
            throw new \Exception('Learning session log not found');
        }

        return $log;
    }

    public function runLearningSessionLog(LearningSession $learningSession) {
        return $learningSession->logs()->create([
            'type' => LearningSessionLogType::START,
            'occurred_at' => now(),
        ]);
    }

    public function resumeLearningSessionLog(LearningSession $learningSession) {
        return $learningSession->logs()->create([
            'type' => LearningSessionLogType::RESUME,
            'occurred_at' => now(),
        ]);
    }

    public function pauseLearningSessionLog(LearningSession $learningSession) {
        return $learningSession->logs()->create([
            'type' => LearningSessionLogType::PAUSE,
            'occurred_at' => now(),
        ]);
    }

    public function stopLearningSessionLog(LearningSession $learningSession) {
        return $learningSession->logs()->create([
            'type' => LearningSessionLogType::STOP,
            'occurred_at' => now(),
        ]);
    }
}
