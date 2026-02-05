<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mastery extends Model
{
    protected $fillable = ['user_id', 'name'];

    /**
     * ==============
     * RELATIONS
     * ==============
     */

    /**
     * Get the user that owns the Mastery
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * The learnings that belong to the Mastery
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function learnings(): HasMany
    {
        return $this->hasMany(Learning::class);
    }

}
