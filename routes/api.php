<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('users', 'UserController@index');
Route::post('login', 'UserController@login');

Route::get('tasks', 'TaskController@index');
Route::get('tasks/completed', 'TaskController@completedTasks');
Route::get('tasks/{task}', 'TaskController@show');
Route::post('tasks/{task}/complete', 'TaskController@complete');
Route::post('tasks/{task}/submit', 'TaskController@submit');

Route::get('pokemons', 'PokemonController@index');
Route::get('pokemons/owned', 'PokemonController@ownedPokemons');
Route::get('pokemons/{pokemon}', 'PokemonController@show');
