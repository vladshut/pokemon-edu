<?php

namespace Tests\Feature;

use App\Pokemon;
use App\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testIndex(): void
    {
        $task = factory(Task::class)->create();

        $taskData = [
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'answerTemplate' => $task->answerTemplate,
        ];

        $this->json('GET', 'api/tasks')
            ->assertStatus(200)
            ->assertJson([$taskData]);
    }

    public function testShow(): void
    {
        $task = factory(Task::class)->create();

        $taskData = [
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'answerTemplate' => $task->answerTemplate,
        ];

        $this->json('GET', 'api/tasks/'.$task->id)
            ->assertStatus(200)
            ->assertJson($taskData);
    }


    public function testComplete(): void
    {
        $user = $this->login();
        $pokemonAdded = factory(Pokemon::class)->create();
        $user->pokemons()->attach([$pokemonAdded->id]);
        $user->save();

        /** @var Task $task */
        $task = factory(Task::class)->create();
        /** @var Pokemon $pokemon */
        $pokemon = factory(Pokemon::class)->create();

        $pokemonData = [
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'imageUrl' => $pokemon->getImageUrlAttribute(),
        ];

        $this->json('POST', 'api/tasks/'.$task->id.'/complete')
            ->assertStatus(200)
            ->assertJson($pokemonData);

        $this->assertCount(2, $user->pokemons()->get()->toArray());
        $this->assertCount(1, $user->completedTasks()->get()->toArray());
    }

    public function testCompletedTask(): void
    {
        $user = $this->login();
        $taskAdded = factory(Task::class)->create();
        $user->completedTasks()->attach([$taskAdded->id]);
        $user->save();

        $taskData = [
            'id' => $taskAdded->id,
            'name' => $taskAdded->name,
            'description' => $taskAdded->description,
            'answerTemplate' => $taskAdded->answerTemplate,
        ];

        $task = factory(Task::class)->create();

        $this->json('GET', 'api/tasks/completed')
            ->assertStatus(200)
            ->assertJson([$taskData]);
    }
}
