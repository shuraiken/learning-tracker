<?php

namespace App\Enums;

enum LearningSessionStatus: string
{
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
}
