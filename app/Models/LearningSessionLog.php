<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningSessionLog extends Model
{
    protected $fillable = [
        'learning_session_id',
        'start_time',
        'end_time',
        'hours_spent'
    ];

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
