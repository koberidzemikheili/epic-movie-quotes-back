<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Resources\UserResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function usermovies()
	{
		$user = Auth::user();
		$movies = Movie::where('user_id', $user->id)->with('genres', 'quotes')->get();
		return response()->json(['movies' => MovieResource::collection($movies)], 200);
	}

	public function getAuthUserData(Request $request)
	{
		$user = $request->user()->load([
			'notificationsReceived' => function ($query) {
				$query->orderBy('created_at', 'desc');
			},
			'notificationsReceived.actor',
		]);

		return response()->json(['user' => new UserResource($user)], 200);
	}
}
