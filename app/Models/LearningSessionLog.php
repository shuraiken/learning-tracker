<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\States\LearningSessionLog\LearningSessionLogStateContract;

class LearningSessionLog extends Model
{
    protected $fillable = [
        'learning_session_id',
        'start_time',
        'end_time',
        'hours_spent'
    ];

    public function state(): LearningSessionLogStateContract
    {
        return match($this->status) {
            LearningSessionLogStatus::RUNNING->value => new RunningState($this),
            LearningSessionLogStatus::PAUSED->value => new PausedState($this),
            LearningSessionLogStatus::COMPLETED->value => new CompletedState($this),
        }
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Get the learningSession that owns the LearningSessionLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function learningSession(): BelongsTo
    {
        return $this->belongsTo(LearningSession::class);
    }
}
