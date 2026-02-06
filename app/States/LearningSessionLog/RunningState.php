<?php

namespace App\States\LearningSessionLog;

use App\States\LearningSessionLog\LearningSessionLogState;

class RunningState extends LearningSessionLogState
{
    public function resume(): void
    {
        $this->learningSessionLog->setStatus('running');
    }

    public function pause(): void
    {
        $this->learningSessionLog->setStatus('paused');
    }
}
