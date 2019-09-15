<?php

namespace Tests\Feature;

use App\Pokemon;
use App\Task;
use Tests\TestCase;

class AwardTest extends TestCase
{
    public function testIndex(): void
    {
        $user = $this->login();

        /** @var Pokemon $pokemon */
        $pokemon = factory(Pokemon::class)->create();

        /** @var Pokemon $pokemon */
        $task = factory(Task::class)->create();

        $award = $user->awardWithPokemonForTheCompletingTask($pokemon, $task);
        $user->save();

        $awardData = [
            'id' => $award->id,
            'pokemon' => [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'imageUrl' => $pokemon->getImageUrlAttribute(),
            ],
            'task' => [
                'id' => $task->id,
                'name' => $task->name,
                'theory' => $task->theory,
                'description' => $task->description,
            ],
        ];

        $this->json('GET', 'api/awards')
            ->assertStatus(200)
            ->assertJson([$awardData]);
    }

    public function testShow(): void
    {
        $user = $this->login();

        /** @var Pokemon $pokemon */
        $pokemon = factory(Pokemon::class)->create();

        /** @var Pokemon $pokemon */
        $task = factory(Task::class)->create();

        $award = $user->awardWithPokemonForTheCompletingTask($pokemon, $task);
        $user->save();

        $awardData = [
            'id' => $award->id,
            'pokemon' => [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'imageUrl' => $pokemon->getImageUrlAttribute(),
            ],
            'task' => [
                'id' => $task->id,
                'name' => $task->name,
                'theory' => $task->theory,
                'description' => $task->description,
            ],
        ];

        $this->json('GET', 'api/awards/' . $award->id)
            ->assertStatus(200)
            ->assertJson($awardData);
    }
}
