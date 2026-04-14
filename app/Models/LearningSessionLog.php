<?php

namespace App\Models;

use App\Enums\LearningSessionStatus;
use App\Models\LearningSession;
use Illuminate\Database\Eloquent\Model;
use App\States\LearningSessionLog\PausedState;
use App\States\LearningSessionLog\RunningState;
use App\States\LearningSessionLog\CompletedState;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\States\LearningSessionLog\LearningSessionLogStateContract;

class LearningSessionLog extends Model
{
    protected $fillable = [
        'learning_session_id',
        'start_time',
        'end_time',
        'hours_spent',
        'paused_at',
        'status',
    ];

    public function state(): LearningSessionLogStateContract
    {
        return match($this->status) {
            LearningSessionStatus::RUNNING->value => new RunningState($this),
            LearningSessionStatus::PAUSED->value => new PausedState($this),
            LearningSessionStatus::COMPLETED->value => new CompletedState($this),
        };
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
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
