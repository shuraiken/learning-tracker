<?php

namespace App\Services;

use App\Models\LearningSessionLog;
use App\Enums\LearningSessionLogStatus;

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

    public function createLearningSessionLog(array $data)
    {
        return LearningSessionLog::create([
            'learning_session_id' => $data['learningSessionId'],
            'start_time' => now(),
            'status' => LearningSessionLogStatus::RUNNING->value,
        ]);
    }

    public function pauseLearningSessionLog(int $learningSessionLogId)
    {
        $learningSessionLog = $this->findLearningSessionLog($learningSessionLogId);

        $learningSessionLog->update([
            'end_time' => now(),
            'status' => LearningSessionLogStatus::PAUSED->value,
        ]);
    }

    public function endLog(int $learningSessionLogId)
    {
        $learningSessionLog = $this->findLearningSessionLog($learningSessionLogId);

        $learningSessionLog->update([
            'end_time' => now(),
            'status' => LearningSessionLogStatus::COMPLETED->value,
        ]);
    }

    public function findRunningLog(int $learningSessionId)
    {
        return LearningSessionLog::query()
            ->where('learning_session_id', $learningSessionId)
            ->where('status', LearningSessionLogStatus::RUNNING->value)
            ->first();
    }
}
