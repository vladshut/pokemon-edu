<?php

namespace Tests\Feature;

use App\Pokemon;
use App\Task;
use Tests\TestCase;

class PokemonTest extends TestCase
{
    public function testIndex(): void
    {
        /** @var Pokemon $pokemon */
        $pokemon = factory(Pokemon::class)->create();

        $pokemonData = [
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'imageUrl' => $pokemon->getImageUrlAttribute(),
        ];

        $this->json('GET', 'api/pokemons')
            ->assertStatus(200)
            ->assertJson([$pokemonData]);
    }

    public function testShow(): void
    {
        /** @var Pokemon $pokemon */
        $pokemon = factory(Pokemon::class)->create();

        $pokemonData = [
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'imageUrl' => $pokemon->getImageUrlAttribute(),
        ];

        $this->json('GET', 'api/pokemons/'.$pokemon->id)
            ->assertStatus(200)
            ->assertJson($pokemonData);
    }
}
