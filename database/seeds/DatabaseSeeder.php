<?php

use App\Pokemon;
use App\Task;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/data/pokemons.json');
        $data = json_decode($json, true);
        $data = array_slice($data, 0, 10);

        $ids = [];
        $names = [];
        foreach ($data as $datum) {
            $number = $datum['number'];
            $name = $datum['number'];

            if (in_array($number, $ids, true) || in_array($name, $names, true)) {
                continue;
            }

            $ids[] = $number;
            $names[] = $name;
            Pokemon::create([
                'id' => $number,
                'name' => $name,
            ]);
        }

        Task::create([
            'name' => 'First task',
            'description' => 'Just do it!',
            'theory' => 'some theory',
            'answerTemplate' => '
            function add(a: number, b: number): number {}',
            'successCriteria' => '_test_.dataProvider = [
            { a: 2, b: 3, expected: 5 },
            { a: 14, b: 15, expected: 29 },
            { a: 12, b: 13, expected: 25 },
            { a: 22, b: 13, expected: 35 },
                    ];
            _test_.testFunction = data => {
                return add(data.a, data.b) === data.expected;
            };',
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }
}
