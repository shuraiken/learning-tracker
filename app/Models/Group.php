<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = ['name'];

    /**
     * Get all of the learnings for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function learnings(): HasMany
    {
        return $this->hasMany(Learning::class);
    }
}
