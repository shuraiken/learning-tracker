<?php

namespace App\States\LearningSessionLog;

use App\States\LearningSessionLog\LearningSessionLogState;

class PausedState extends LearningSessionLogState
{
    public function resume(): void
    {
        $this->learningSessionLog->setStatus('running');
    }

    public function stop(): void
    {
        $this->learningSessionLog->setStatus('completed');
    }
}
