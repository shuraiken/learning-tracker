<?php

namespace App\States\LearningSessionLog;

interface LearningSessionLogStateContract
{
    public function __construct(LearningSessionLog $learningSessionLog);
    public function resume(): void;
    public function pause(): void;
    public function stop(): void;
}
