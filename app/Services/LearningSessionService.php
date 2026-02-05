<?php

namespace App\Services;

use App\Exceptions\ActiveSessionAlreadyExistsException;
use App\Exceptions\CurrentSessionNotFoundException;
use App\Models\LearningSession;
use App\Enums\LearningSessionStatus;

class LearningSessionService
{
    public function __construct(private LearningSessionLogService $learningSessionLogService)
    {
    }

    public function getCurrentSession()
    {
        return LearningSession::query()
            ->where('user_id', auth()->user()->id)
            ->where('status', LearningSessionStatus::ACTIVE->value)
            ->latest()
            ->first();
    }

    public function checkIfActiveSessionExists()
    {
        return $this->getCurrentSession() ? true : false;
    }

    public function createLearningSession(array $data)
    {
        if ($this->checkIfActiveSessionExists()) {
            throw new ActiveSessionAlreadyExistsException();
        }

        return LearningSession::create([
            'learning_id' => $data['learningId'],
            'name' => $data['name'] ?? null,
            'started_at' => now(),
            'status' => LearningSessionStatus::ACTIVE->value,
            'note' => $data['note'] ?? null
        ]);
    }

    public function pauseLearningSession(LearningSession $learningSession)
    {
        $learningSession->update([
            'status' => LearningSessionStatus::PAUSED->value,
        ]);

        $runningLog = $this->learningSessionLogService->findRunningLog($learningSession->id);

        if ($runningLog) {
            $this->learningSessionLogService->pauseLearningSessionLog($runningLog->id);
        }
    }
}
