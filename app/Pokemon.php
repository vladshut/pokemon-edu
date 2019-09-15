<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

/**
 * @property int id
 * @property string name
 */
class Pokemon extends Model
{
    protected $appends = ['imageUrl'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Scope a query to only include users of a given type.
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeNotOwnedByUser(Builder $query, User $user): Builder
    {
        $ids = $user->awards()->get(['pokemon_id'])->toArray();

        if (!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        return $query;
    }

    public function getImageUrlAttribute(): string
    {
        return URL::to('/img/' . $this->id . '.png');
    }
}
