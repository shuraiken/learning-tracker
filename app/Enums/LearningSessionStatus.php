<?php

namespace App\Enums;

enum LearningSessionStatus: string
{
    case STARTED = 'started';
    case RUNNING = 'running';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
}
