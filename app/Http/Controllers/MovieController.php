<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
	public function show(Movie $movie): JsonResponse
	{
		$movie->load(['genres', 'quotes.comments', 'quotes.likes']);
		return response()->json(['movie' => new MovieResource($movie)], 200);
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = Movie::create([
			'name'        => ['en' => $request->name['en'], 'ka' => $request->name['ka']],
			'year'        => $request->year,
			'director'    => ['en' => $request->director['en'], 'ka' => $request->director['ka']],
			'description' => ['en' => $request->description['en'], 'ka' => $request->description['ka']],
			'user_id'     => Auth::id(),
			'movie_image' => $request->file('movie_image')->store('movie_images'),
		]);

		$movie->genres()->attach($request->genres);

		return response()->json(['message' => 'success'], 201);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		$this->authorize('update', $movie);

		if ($request->hasFile('movie_image')) {
			Storage::delete($movie->movie_image);
			$movie->movie_image = $request->file('movie_image')->store('movie_images');
		}

		$movie->update([
			'name'        => ['en' => $request->name['en'], 'ka' => $request->name['ka']],
			'year'        => $request->year,
			'director'    => ['en' => $request->director['en'], 'ka' => $request->director['ka']],
			'description' => ['en' => $request->description['en'], 'ka' => $request->description['ka']],
			'user_id'     => Auth::id(),
			'movie_image' => $movie->movie_image,
		]);

		if ($request->has('genres')) {
			$genreIds = array_map(function ($genre) {
				return $genre['id'];
			}, $request->genres);

			$movie->genres()->sync($genreIds);
		}

		return response()->json(['message' => 'success'], 200);
	}

public function destroy(Movie $Movie): JsonResponse
{
	$this->authorize('delete', $Movie);

	$Movie->delete();

	return response()->json(['message' => 'success'], 201);
}
}
