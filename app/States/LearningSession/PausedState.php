<?php

namespace App\States\LearningSession;

use App\States\LearningSession\LearningSessionState;

class PausedState extends LearningSessionState
{
    public function activate(): void
    {
        $this->learningSession->setStatus('active');
    }

    public function complete(): void
    {
        $this->learningSession->setStatus('completed');
    }
}
