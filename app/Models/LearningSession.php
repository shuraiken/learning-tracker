<?php

namespace App\Models;

use App\Enums\LearningSessionStatus;
use Illuminate\Database\Eloquent\Model;
use App\States\LearningSession\PausedState;
use App\States\LearningSession\RunningState;
use App\States\LearningSession\StartedState;
use App\States\LearningSession\CompletedState;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\States\LearningSession\LearningSessionStateContract;

class LearningSession extends Model
{
    protected $fillable = [
        'user_id',
        'learning_id',
        'name',
        'started_at',
        'ended_at',
        'hours_spent',
        'note',
        'status',
    ];

    public function state(string $status): LearningSessionStateContract
    {
        return match($status) {
            LearningSessionStatus::STARTED->value => new StartedState($this),
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
     * Get the learning that owns the LearningSession
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function learning(): BelongsTo
    {
        return $this->belongsTo(Learning::class);
    }
}
