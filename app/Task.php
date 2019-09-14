<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public static function scopeCompletedByUser(Builder $query, User $user): Builder
    {
        $ids = $user->completedTasks()->get(['task_id'])->toArray();

        return $query->whereIn('id', $ids);
    }
}
