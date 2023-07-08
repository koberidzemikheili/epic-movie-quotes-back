<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Resources\UserResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditUser\EditUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	public function userMovies(): JsonResponse
	{
		$user = Auth::user();
		$movies = Movie::where('user_id', $user->id)->with('genres', 'quotes')->get();
		return response()->json(['movies' => MovieResource::collection($movies)], 200);
	}

	public function showUser(User $user)
	{
		return response()->json(['user' => new UserResource($user)], 200);
	}

	public function getAuthUserData(Request $request): JsonResponse
	{
		$user = $request->user()->load([
			'notificationsReceived' => function ($query) {
				$query->orderBy('created_at', 'desc');
			},
			'notificationsReceived.actor', 'notificationsReceived.notifiable',
		]);

		return response()->json(['user' => new UserResource($user)], 200);
	}

	public function update(EditUserRequest $request): JsonResponse
	{
		$attributes = $request->validated();

		$user = Auth::user();

		if ($user) {
			if (request()->file('profile_picture')) {
				$attributes['profile_picture'] = request()->file('profile_picture')->store('profile_pictures');
				$user->profile_pictures = $attributes['profile_picture'];
			}

			if (request()->has('email') && $attributes['email'] !== $user->email) {
				$user->new_email = $attributes['email'];
				$user->save();
				$user->sendNewEmailVerificationNotification();
				unset($attributes['email']);
			}

			$user->update($attributes);
		}

		return response()->json([
			'message' => 'Successfully edited user',
		], 201);
	}
}
