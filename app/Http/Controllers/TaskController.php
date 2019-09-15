<?php

namespace App\Http\Controllers;

use App\Exceptions\JsNotExecuted;
use App\Exceptions\TsNotCompiled;
use App\Pokemon;
use App\Services\TaskAnswerChecker;
use App\Task;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
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


    /**
     * Submit answer to the the task.
     *
     * @param Request $request
     * @param TaskAnswerChecker $taskAnswerChecker
     * @param Task $task
     * @return Pokemon|JsonResponse
     */
    public function submit(Request $request, TaskAnswerChecker $taskAnswerChecker, Task $task)
    {
        /** @var User $user */
        $user = auth()->user();

        $answer = (string)$request->get('answer');

        try {
            $result = $taskAnswerChecker->check($task, $answer, $user);
        } catch (TsNotCompiled $e) {
            return new JsonResponse(['error' =>  'Code not compiled!', 'error_data' => $e->getMessage()], 400);
        } catch (JsNotExecuted $e) {
            return new JsonResponse(['error' =>  'Code not executed!'], 400);
        }

        if (in_array(false, $result, true)) {
            return new JsonResponse(['error' => 'Tests not passed!', 'error_data' => $result], 400);
        }

        $user->complete($task);
        /** @var Pokemon $pokemon */

        $pokemon = Pokemon::inRandomOrder()->notOwnedByUser($user)->limit(1)->first();
        $user->award($pokemon);
        $user->save();

        return $pokemon;
    }
}
