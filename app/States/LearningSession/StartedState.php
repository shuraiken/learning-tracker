<?php

namespace App\States\LearningSession;

use App\Services\LearningSessionService;
use App\States\LearningSession\LearningSessionState;

class StartedState extends LearningSessionState
{
    public function resume(): void
    {
        $this->learningSession->setStatus('running');
    }
}
