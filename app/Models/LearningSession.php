<?php

namespace App\Models;

use App\Enums\LearningSessionStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\States\LearningSession\PausedState;
use App\States\LearningSession\ActiveState;
use App\States\LearningSession\CompletedState;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Contracts\LearningSessionStateContract;

class LearningSession extends Model
{
    protected $fillable = [
        'learning_id',
        'name',
        'started_at',
        'ended_at',
        'note',
        'status',
        'total_duration',
    ];

    protected $appends = ['latest_log'];

    public function state(string $status): LearningSessionStateContract
    {
        return match($status) {
            LearningSessionStatus::ACTIVE->value => new ActiveState($this),
            LearningSessionStatus::PAUSED->value => new PausedState($this),
        };
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }

    // ACCESSORS

    protected function latestLog(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->logs()->latest()->first(),
        );
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

    /**
     * Get the learning that owns the LearningSession
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(LearningSessionLog::class);
    }
}
