<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Learning extends Model
{
    protected $fillable = [
        'user_id',
        'group_id',
        'mastery_id',
        'name',
        'description',
    ];

    /**
     * ==============
     * LEARNINGS
     * ==============
     */

    /**
     * Get the group that owns the Learning
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the mastery that owns the Learning
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mastery(): BelongsTo
    {
        return $this->belongsTo(Mastery::class);
    }

    /**
     * Get the user that owns the Learning
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
