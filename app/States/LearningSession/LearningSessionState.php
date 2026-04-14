<?php

namespace App\States\LearningSession;

use App\Models\LearningSession;
use App\States\LearningSession\LearningSessionStateContract;
use Exception;

class LearningSessionState implements LearningSessionStateContract
{
    public function __construct(public LearningSession $learningSession)
    {
    }

    public function start(): void
    {
        throw new Exception();
    }

    public function resume(): void
    {
        throw new Exception();
    }

    public function pause(): void
    {
        throw new Exception();
    }

    public function stop(): void
    {
        throw new Exception();
    }
}
