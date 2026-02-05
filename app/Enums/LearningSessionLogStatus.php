<?php

namespace App\Enums;

enum LearningSessionLogStatus: string
{
    case RUNNING = 'running';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
}
