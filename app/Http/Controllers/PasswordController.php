<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest\EmailRequest;
use App\Http\Requests\PasswordRequest\ResetRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
	public function postResetEmail(EmailRequest $request): JsonResponse
	{
		$request->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);
		return $status === Password::RESET_LINK_SENT
					? response()->json(['status1' => $status], 201)
					: response()->json(['status2' => __($status)], 201);
	}

	public function reset(ResetRequest $request): JsonResponse
	{
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => $password,
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		return $status === Password::PASSWORD_RESET
		? response()->json(['status1' => $status], 201)
		: response()->json(['status2' => __($status)], 201);
	}
}
