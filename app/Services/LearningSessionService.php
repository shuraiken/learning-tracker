<?php

namespace App\Services;

use App\Models\LearningSession;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Enums\LearningSessionStatus;
use App\Exceptions\CurrentSessionNotFoundException;
use App\Exceptions\ActiveSessionAlreadyExistsException;

class LearningSessionService
{
    public function __construct(private LearningSessionLogService $learningSessionLogService)
    {
    }

    public function getCurrentSession()
    {
        return LearningSession::query()
            ->where('user_id', auth()->user()->id)
            ->where('status', LearningSessionStatus::STARTED->value)
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

        $learningSession = LearningSession::create([
            'user_id' => auth()->user()->id,
            'learning_id' => $data['learningId'],
            'name' => $data['name'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => LearningSessionStatus::STARTED->value
        ]);

        return $learningSession;
    }

    public function runLearningSession(int $id)
    {
        DB::transaction(function () use ($id) {
            if (auth()->user()->runningLearningSession()) {
                throw new Exception('You already have a running learning session');
            }

            $learningSession = LearningSession::findOrFail($id);

            $learningSession->state('started')->resume();

            $this->learningSessionLogService->runLearningSessionLog($learningSession);
        });
    }


    public function resumeLearningSession(int $id)
    {
        $learningSession = LearningSession::findOrFail($id);

        $learningSession->state('paused')->resume();
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
