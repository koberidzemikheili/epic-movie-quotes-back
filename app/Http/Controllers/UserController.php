<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function usermovies()
	{
		$user = Auth::user();
		$movies = Movie::where('user_id', $user->id)->with('genres', 'quotes')->get();
		return response()->json(['movies' => $movies], 201);
	}
}
