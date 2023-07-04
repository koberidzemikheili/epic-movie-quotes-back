<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\Register\RegisterRequest;
use App\Http\Requests\Login\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
	public function login(LoginRequest $request): JsonResponse
	{
		$credentials = $request->only(['login', 'password']);
		$field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$credentials[$field] = $credentials['login'];
		unset($credentials['login']);
		if (auth()->attempt($credentials, $request->has('remember'))) {
			return response()->json(['message' => 'User logged in'], 201);
		}
		return response()->json(['message' => trans('validation.login')], 401);
	}

	public function store(RegisterRequest $request): JsonResponse
	{
		$validatedData = $request->validated();

		$validatedData['profile_pictures'] = 'default_profile_picture.jpg';

		$user = User::create($validatedData);
		$user->sendEmailVerificationNotification();

		return response()->json(['message' => 'User created'], 201);
	}

	public function logout(Request $request): JsonResponse
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
		return response()->json([
			'message' => 'Successfully logged out',
		], 201);
	}

	public function redirect(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback(): RedirectResponse
	{
		$googleuser = Socialite::driver('google')->user();
		$user = User::updateOrCreate(
			['email' => $googleuser->email],
			['username' => $googleuser->name, 'email'=>$googleuser->email, 'google_id'=>$googleuser->id, 'profile_pictures'=>'default_profile_picture.jpg'],
		);
		auth()->login($user);
		$user->markEmailAsVerified();
		session()->regenerate();
		$csrfToken = Crypt::encrypt(csrf_token());
		$laravelsession = Crypt::encrypt(session()->getId());
		$response = redirect()->away(env('FRONT_END_URL'))->withCookie('XSRF-TOKEN', $csrfToken)->withCookie('laravel_session', $laravelsession);
		return $response;
	}
}
