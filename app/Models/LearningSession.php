<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningSession extends Model
{
    protected $fillable = [
        'learning_id',
        'name',
        'started_at',
        'ended_at',
        'hours_spent',
        'note'
    ];

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
