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

    public function getCurrentSession(): ?LearningSession
    {
        return LearningSession::query()
            ->whereRelation('learning', 'user_id', auth()->user()->id)
            ->where('status', LearningSessionStatus::ACTIVE->value)
            ->orWhere('status', LearningSessionStatus::PAUSED->value)
            ->latest()
            ->first();
    }

    public function checkIfActiveSessionExists(): bool
    {
        return $this->getCurrentSession() ? true : false;
    }

    public function createLearningSession(array $data): LearningSession
    {
        if ($this->checkIfActiveSessionExists()) {
            throw new ActiveSessionAlreadyExistsException();
        }

        $learningSession = LearningSession::create([
            'learning_id' => $data['learning_id'],
            'name' => $data['name'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => LearningSessionStatus::ACTIVE->value,
        ]);

        return $learningSession;
    }

    public function createLearningSessionAndRunSessionLog(array $data)
    {
        return DB::transaction(function () use ($data) {
            $learningSession = $this->createLearningSession($data);

            $learningSession->update([
                'started_at' => now(),
            ]);

            $learningSessionLog = $this->runLearningSession($learningSession->id);

            return [$learningSession, $learningSessionLog];
        });
    }

    public function runLearningSession(int $id)
    {
        if (auth()->user()->activeLearningSession()) {
            throw new Exception('You already have an active learning session');
        }

        $learningSession = LearningSession::findOrFail($id);

        $learningSession->state('active')->activate();

        return $this->learningSessionLogService->runLearningSessionLog($learningSession);
    }

    public function startLearningSession(int $id)
    {
        $learningSession = LearningSession::findOrFail($id);

        $this->learningSessionLogService->runLearningSessionLog($learningSession);

        return $learningSession;
    }

    public function resumeLearningSession(LearningSession $learningSession)
    {
        $now = now();

        $learningSession->state('paused')->activate();

        return $this->learningSessionLogService->resumeLearningSessionLog($learningSession);
    }

    public function pauseLearningSession(LearningSession $learningSession)
    {
        $now = now();
        $previousLog = $learningSession->logs()->latest()->first();

        $learningSession->state('active')->pause();

        $this->learningSessionLogService->pauseLearningSessionLog($learningSession);

        $segmentDuration = $previousLog
            ? $now->diffInSeconds($previousLog->occured_at)
            : 0;

        $learningSession->increment('total_duration', $segmentDuration);
    }

    // Creates a stop log
    public function stopLearningSession(LearningSession $learningSession)
    {
        $now = now();

        $previousLog = $learningSession->logs()->latest()->first();

        $this->learningSessionLogService->stopLearningSessionLog($learningSession);

        $segmentDuration = $previousLog
            ? $now->diffInSeconds($previousLog->occured_at)
            : 0;

        $learningSession->increment('total_duration', $segmentDuration);
    }

    // Creates a stop log and set learning session as complete
    public function endLearningSession(LearningSession $learningSession)
    {
        $now = now();
        $previousLog = $learningSession->logs()->latest()->first();
        $segmentDuration = $previousLog
            ? $now->diffInSeconds($previousLog->occured_at)
            : 0;

        $latestLog = $this->learningSessionLogService->stopLearningSessionLog($learningSession);

        $learningSession->update([
            'ended_at' => $now,
        ]);
        
        $learningSession->state($learningSession->status)->complete();

        $learningSession->increment('total_duration', $segmentDuration);
    }
}
