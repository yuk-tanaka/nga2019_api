<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait CommonScopes
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeAsc(Builder $query): Builder
    {
        return $query->orderBy('id', 'asc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeDesc(Builder $query): Builder
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * @param Builder $query
     * @return Model|null
     */
    public function scopeNew(Builder $query): ?Model
    {
        return $query->orderBy('created_at', 'desc')->first();
    }
}