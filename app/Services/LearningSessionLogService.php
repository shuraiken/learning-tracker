<?php

namespace App\Services;

use App\Models\LearningSession;
use App\Models\LearningSessionLog;
use App\Enums\LearningSessionStatus;

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
        return LearningSessionLog::create([
            'learning_session_id' => $learningSession->id,
            'start_time' => now(),
            'status' => LearningSessionStatus::RUNNING->value,
        ]);
    }

    public function pauseLearningSessionLog(int $learningSessionLogId)
    {
        $learningSessionLog = $this->findLearningSessionLog($learningSessionLogId);

        $learningSessionLog->update([
            'end_time' => now(),
            'status' => LearningSessionStatus::PAUSED->value,
        ]);
    }

    public function endLog(int $learningSessionLogId)
    {
        $learningSessionLog = $this->findLearningSessionLog($learningSessionLogId);

        $learningSessionLog->update([
            'end_time' => now(),
            'status' => LearningSessionStatus::COMPLETED->value,
        ]);
    }

    public function findRunningLog(int $learningSessionId)
    {
        return LearningSessionLog::query()
            ->where('learning_session_id', $learningSessionId)
            ->where('status', LearningSessionStatus::RUNNING->value)
            ->first();
    }
}
