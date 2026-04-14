<?php

namespace App\States\LearningSession;

use App\Models\LearningSession;

interface LearningSessionStateContract
{
    public function __construct(LearningSession $learningSession);
    public function start(): void;
    public function resume(): void;
    public function pause(): void;
    public function stop(): void;
}
