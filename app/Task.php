<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string successCriteria
 * @property int id
 * @property string name
 * @property string description
 * @property string theory
 * @property string answerTemplate
 */
class Task extends Model
{
    public static function scopeCompletedByUser(Builder $query, User $user): Builder
    {
        $ids = $user->completedTasks()->get(['task_id'])->toArray();

        return $query->whereIn('id', $ids);
    }
}
