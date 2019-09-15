<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        Config::set('jwt.ttl', 60*60*7);

        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'name' => 'Ash Ketchum'
            ]);

            $token = JWTAuth::attempt($credentials);
        }

        return $this->respondWithToken(auth()->getUser(), $token);
    }

    protected function respondWithToken(User $user, $token)
    {
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'awards_count' => $user->awards()->count(),
            'credentials' => $token,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::withCount('awards')->get();
    }
}
