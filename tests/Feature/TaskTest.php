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


    public function testSubmitSuccessPath(): void
    {
        $user = $this->login();
        $pokemonAdded = factory(Pokemon::class)->create();
        $user->pokemons()->attach([$pokemonAdded->id]);
        $user->save();


        $successCriteria = '_test_.dataProvider = [
            { a: 2, b: 3, expected: 5 },
            { a: 14, b: 15, expected: 29 },
            { a: 12, b: 13, expected: 25 },
            { a: 22, b: 13, expected: 35 },
                    ];
            _test_.testFunction = data => {
                return add(data.a, data.b) === data.expected;
            };
        ';

        /** @var Task $task */
        $task = factory(Task::class)->create();
        $task->successCriteria = $successCriteria;
        $task->save();

        $answer = '
            function add(a: number, b: number): number {return a+b;}
        ';

        /** @var Pokemon $pokemon */
        $pokemon = factory(Pokemon::class)->create();

        $pokemonData = [
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'imageUrl' => $pokemon->getImageUrlAttribute(),
        ];


        $this->json('POST', 'api/tasks/'.$task->id.'/submit', ['answer' => $answer])
            ->assertStatus(200)
            ->assertJson($pokemonData);

        $this->assertCount(2, $user->pokemons()->get()->toArray());
        $this->assertCount(1, $user->completedTasks()->get()->toArray());
    }


    public function testSubmitTsNotCompiledException(): void
    {
        $this->login();

        $successCriteria = '_test_.dataProvider = [
            { a: 2, b: 3, expected: 5 },
            { a: 14, b: 15, expected: 29 },
            { a: 12, b: 13, expected: 25 },
            { a: 22, b: 13, expected: 35 },
                    ];
            _test_.testFunction = data => {
                return add(data.a, data.b) === data.expected;
            };
        ';

        /** @var Task $task */
        $task = factory(Task::class)->create();
        $task->successCriteria = $successCriteria;
        $task->save();

        $answer = '
            function add(a: number, b: boolean): number {return a+b;}
        ';

        $this->json('POST', 'api/tasks/'.$task->id.'/submit', ['answer' => $answer])
            ->assertStatus(400)
            ->assertJson(['error' =>  'Code not compiled!', 'error_data' => "code.ts(1,53): error TS2365: Operator '+' cannot be applied to types 'number' and 'boolean'."]);
    }


    public function testSubmitTestNotPassed(): void
    {
        $this->login();

        $successCriteria = '_test_.dataProvider = [
            { a: 2, b: 3, expected: 5 },
            { a: 14, b: 15, expected: 29 },
            { a: 12, b: 13, expected: 25 },
            { a: 22, b: 13, expected: 35 },
                    ];
            _test_.testFunction = data => {
                return add(data.a, data.b) === data.expected;
            };
        ';

        /** @var Task $task */
        $task = factory(Task::class)->create();
        $task->successCriteria = $successCriteria;
        $task->save();

        $answer = '
            function add(a: number, b: number): number {return 2*a + b;}
        ';

        $this->json('POST', 'api/tasks/'.$task->id.'/submit', ['answer' => $answer])
            ->assertStatus(400)
            ->assertJson(['error' =>  'Tests not passed!', 'error_data' => [false, false, false, false]]);
    }


    public function testSubmitVulnerableCodeNotExecuted(): void
    {
        $this->login();

        $successCriteria = '_test_.dataProvider = [
            { a: 2, b: 3, expected: 5 },
            { a: 14, b: 15, expected: 29 },
            { a: 12, b: 13, expected: 25 },
            { a: 22, b: 13, expected: 35 },
                    ];
            _test_.testFunction = data => {
                return add(data.a, data.b) === data.expected;
            };
        ';

        /** @var Task $task */
        $task = factory(Task::class)->create();
        $task->successCriteria = $successCriteria;
        $task->save();

        $answer = '
            function add(a: number, b: number): void {process.exit();}
        ';

        $this->json('POST', 'api/tasks/'.$task->id.'/submit', ['answer' => $answer])
            ->assertStatus(400)
            ->assertJson(['error' =>  'Code not executed!']);
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
