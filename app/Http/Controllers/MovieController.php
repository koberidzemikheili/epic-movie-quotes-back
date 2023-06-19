<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
	public function show(Movie $movie)
	{
		$user = Auth::user();
		$curretmovie = $user->id;
		$quotes = $movie->quotes;
		return response()->json(['movie' => $movie, 'genre'=>$movie->genres, 'quotes'=>$quotes, 'user'=>$curretmovie], 201);
	}

	public function usermovies()
	{
		$user = Auth::user();
		$movies = Movie::where('user_id', $user->id)->with('genres', 'quotes')->get();
		return response()->json(['movies' => $movies], 201);
	}

public function store(StoreMovieRequest $request)
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

public function update(UpdateMovieRequest $request, Movie $movie)
{
	$movie->setTranslation('name', 'en', $request->name['en']);
	$movie->setTranslation('name', 'ka', $request->name['ka']);
	$movie->year = $request->year;
	$movie->setTranslation('director', 'en', $request->director['en']);
	$movie->setTranslation('director', 'ka', $request->director['ka']);
	$movie->setTranslation('description', 'en', $request->description['en']);
	$movie->setTranslation('description', 'ka', $request->description['ka']);
	$movie->user_id = Auth::id();

	if ($request->hasFile('movie_image')) {
		Storage::delete($movie->movie_image);
		$attributes['movie_image'] = request()->file('movie_image')->store('movie_images');
		$movie->movie_image = $attributes['movie_image'];
	}

	$movie->save();

	if ($request->has('genres')) {
		$genreIds = array_map(function ($genre) {
			return $genre['id'];
		}, $request->genres);

		$movie->genres()->sync($genreIds);
	}

	return response()->json(['message' => 'success'], 200);
}

public function destroy(Movie $Movie)
{
	$Movie->delete();

	return response()->json(['message' => 'success'], 200);
}
}
