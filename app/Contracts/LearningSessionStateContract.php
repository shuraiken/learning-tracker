<?php

namespace App\Contracts;

use App\Models\LearningSession;

interface LearningSessionStateContract
{
    public function __construct(LearningSession $learningSession);
    public function activate(): void;
    public function pause(): void;
    public function complete(): void;
}
