<?php

namespace App\Http\Controllers;

use App\Pokemon;
use App\Task;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Task[]|Collection
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return Task
     */
    public function show(Task $task): Task
    {
        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @return Collection
     */
    public function completedTasks(): Collection
    {
        /** @var User $user */
        $user = auth()->user();
        return Task::completedByUser($user)->get();
    }

    /**
     * Submit answer to the the task.
     *
     * @param Task $task
     * @return Pokemon
     */
    public function complete(Task $task): Pokemon
    {
        /** @var User $user */
        $user = auth()->user();

        $user->complete($task);
        /** @var Pokemon $pokemon */

        $pokemon = Pokemon::inRandomOrder()->notOwnedByUser($user)->limit(1)->first();
        $user->award($pokemon);
        $user->save();

        return $pokemon;
    }
}
