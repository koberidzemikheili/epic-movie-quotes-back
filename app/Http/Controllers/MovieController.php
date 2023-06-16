<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
	public function show(Movie $movie)
	{
		return response()->json(['movie' => $movie, 'genre'=>$movie->genres], 201);
	}

public function store(StoreQuoteRequest $request)
{
	$movie = new Movie();
	$movie->setTranslation('name', 'en', $request->name['en']);
	$movie->setTranslation('name', 'ka', $request->name['ka']);
	$movie->year = $request->year;
	$movie->setTranslation('director', 'en', $request->director['en']);
	$movie->setTranslation('director', 'ka', $request->director['ka']);
	$movie->setTranslation('description', 'en', $request->description['en']);
	$movie->setTranslation('description', 'ka', $request->description['ka']);
	$movie->user_id = Auth::id();
	$attributes['movie_image'] = request()->file('movie_image')->store('movie_images');
	$movie->movie_image = $attributes['movie_image'];

	$movie->save();

	$movie->genres()->attach($request->genres);

	return response()->json(['message' => 'success'], 201);
}
}
