<?php

namespace App\States\LearningSession;

use App\States\LearningSession\LearningSessionState;

class ActiveState extends LearningSessionState
{
    public function pause(): void
    {
        $this->learningSession->setStatus('paused');
    }

    public function complete(): void
    {
        $this->learningSession->setStatus('completed');
    }
}
