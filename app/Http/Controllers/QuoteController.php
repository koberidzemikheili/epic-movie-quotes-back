<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
	public function store(StoreQuoteRequest $request)
	{
		$quote = new Quote();
		$quote->title = $request->title;
		$quote->movie_id = $request->movie_id;
		$attributes['quote_image'] = request()->file('quote_image')->store('quote_images');
		$quote->quote_image = $attributes['quote_image'];
		$quote->user_id = Auth::id();

		$quote->save();

		return response()->json(['message' => 'Quote created successfully'], 201);
	}
}
