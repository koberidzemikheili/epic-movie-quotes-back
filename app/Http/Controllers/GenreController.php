<?php

namespace App\Http\Controllers;

use App\Http\Resources\GenreResource;
use App\Models\Genre;

class GenreController extends Controller
{
	public function index()
	{
		$genres = Genre::all();
		return response()->json(['genres' => GenreResource::collection($genres)], 200);
	}
}
