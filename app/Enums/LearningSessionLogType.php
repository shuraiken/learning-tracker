<?php

namespace App\Enums;

enum LearningSessionLogType: string
{
    case START = 'start';
    case PAUSE = 'pause';
    case RESUME = 'resume';
    case STOP = 'stop';
}
