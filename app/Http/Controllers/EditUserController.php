<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUser\EditUserRequest;
use Illuminate\Support\Facades\Auth;

class EditUserController extends Controller
{
	public function update(EditUserRequest $request)
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
