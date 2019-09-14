<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegistration()
    {
        $payload = ['email' => 'testlogin@user.com', 'password' => 'toptal123'];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'credentials',
                'pokemons_count',
            ]);

        $this->assertDatabaseHas('users', Arr::only($payload, ['user_name']));
    }

    public function testLogin()
    {
        factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => bcrypt('toptal123'),
        ]);

        $payload = ['email' => 'testlogin@user.com', 'password' => 'toptal123'];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'credentials',
                'pokemons_count'
            ]);

        $this->assertDatabaseHas('users', Arr::only($payload, ['user_name']));
    }

    public function testIndex()
    {
        $usersCount = 5;
        $users = factory(User::class, $usersCount)->create();
        $this->login($users[0]);


        $this->json('GET', 'api/users')
            ->assertStatus(200)
            ->assertJsonStructure(["*" => [
                'id',
                'email',
                'name',
                'pokemons_count',
            ]])
            ->assertJsonCount($usersCount);
    }

}
