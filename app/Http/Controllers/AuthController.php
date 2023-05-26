<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\Register\RegisterRequest;
use App\Http\Requests\Login\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function login(LoginRequest $request)
	{
		$credentials = $request->only(['login', 'password']);
		$field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$credentials[$field] = $credentials['login'];
		unset($credentials['login']);
		auth()->attempt($credentials, $request->has('remember'));
		$user = User::where($field, $request->login)->first();
		$token = $user->createToken('authToken')->plainTextToken;
		session()->regenerate();
		return response()->json(['message' => 'User logged in', 'token' => $token], 201);
	}

	public function store(RegisterRequest $request)
	{
		User::create($request->validated());
		return response()->json(['message' => 'User created'], 201);
	}

	public function logout(Request $request)
	{
		$request->user()->tokens()->delete();

		return response()->json([
			'message' => 'Successfully logged out',
		]);
	}
}
