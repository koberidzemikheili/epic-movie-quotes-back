<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomEmailVerificationRequest;
use App\Http\Requests\CustomNewEmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerificationController extends Controller
{
	public function verifyEmail(CustomEmailVerificationRequest $request): RedirectResponse
	{
		$request->fulfill();

		return redirect(env('FRONT_END_URL') . '/verified-successfully');
	}

	public function verifyNewEmail(CustomNewEmailVerificationRequest $request): RedirectResponse
	{
		$request->fulfill();

		return redirect(env('FRONT_END_URL') . '/profile-page');
	}
}
