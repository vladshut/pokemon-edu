<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function completedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    public function pokemons(): BelongsToMany
    {
        return $this->belongsToMany(Pokemon::class);
    }

    public function complete(Task $task): void
    {
        $this->completedTasks()->sync([$task->id], false);
    }

    public function award(Pokemon $pokemon): void
    {
        $this->pokemons()->sync([$pokemon->id], false);
    }
}
