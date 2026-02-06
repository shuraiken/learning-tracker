<?php

namespace App\States\LearningSessionLog;

use App\Models\LearningSessionLog;
use App\States\LearningSessionLog\LearningSessionLogStateContract;
use Exception;

class LearningSessionLogState implements LearningSessionLogStateContract
{
    public function __construct(public LearningSessionLog $learningSessionLog)
    {
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
