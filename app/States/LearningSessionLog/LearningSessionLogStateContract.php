<?php

namespace App\States\LearningSessionLog;

use App\Models\LearningSession;

interface LearningSessionLogStateContract
{
    public function __construct(LearningSession $learningSession);

    public function resume(): void;
    public function pause(): void;
    public function stop(): void;
}
