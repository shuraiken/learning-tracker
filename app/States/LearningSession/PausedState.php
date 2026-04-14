<?php

namespace App\States\LearningSession;

use App\States\LearningSession\LearningSessionState;

class PausedState extends LearningSessionState
{
    public function resume(): void
    {
        $this->learningSession->setStatus('running');
    }

    public function stop(): void
    {
        $this->learningSession->setStatus('completed');
    }
}
