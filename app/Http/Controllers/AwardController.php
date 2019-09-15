<?php

namespace App\Http\Controllers;

use App\Award;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Award[]|Collection
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->awards()->get();
    }

    /**
     * Display the specified resource.
     *
     * @param Award $award
     * @return Award
     */
    public function show(Award $award): Award
    {
        return $award;
    }
}
