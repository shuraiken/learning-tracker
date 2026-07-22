<?php

namespace App\States\LearningSession;

use App\Models\LearningSession;
use App\Contracts\LearningSessionStateContract;
use Exception;

class LearningSessionState implements LearningSessionStateContract
{
    public function __construct(public LearningSession $learningSession)
    {
    }

    public function activate(): void
    {
        throw new Exception();
    }

    public function pause(): void
    {
        throw new Exception();
    }

    public function complete(): void
    {
        throw new Exception();
    }
}
