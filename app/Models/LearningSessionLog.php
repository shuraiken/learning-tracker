<?php

namespace App\Models;

use App\Models\LearningSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningSessionLog extends Model
{
    protected $fillable = [
        'learning_session_id',
        'type',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
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
