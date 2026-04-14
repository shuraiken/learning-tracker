<?php

namespace App\States\LearningSession;

use App\States\LearningSession\LearningSessionState;

class RunningState extends LearningSessionState
{
    public function resume(): void
    {
        $this->learningSession->setStatus('running');
    }

    public function pause(): void
    {
        $this->learningSession->setStatus('paused');
    }
}
