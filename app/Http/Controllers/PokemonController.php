<?php

namespace App\Http\Controllers;

use App\Pokemon;
use Illuminate\Database\Eloquent\Collection;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Pokemon[]|Collection
     */
    public function index()
    {
        return Pokemon::all();
    }

    /**
     * Display the specified resource.
     *
     * @param Pokemon $pokemon
     * @return Pokemon
     */
    public function show(Pokemon $pokemon): Pokemon
    {
        return $pokemon;
    }
}
