<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Http\Requests\Quote\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

	public function update(UpdateQuoteRequest $request, Quote $quote)
	{
		$quote->setTranslation('title', 'en', $request->title['en']);
		$quote->setTranslation('title', 'ka', $request->title['ka']);
		$quote->user_id = Auth::id();

		if ($request->hasFile('quote_image')) {
			Storage::delete($quote->quote_image);
			$attributes['quote_image'] = request()->file('quote_image')->store('quote_images');
			$quote->quote_image = $attributes['quote_image'];
		}

		$quote->save();

		return response()->json(['message' => 'success'], 200);
	}

	public function index($id)
	{
		$quote = Quote::with('likes', 'comments')->find($id);

		if (!$quote) {
			return response()->json(['error' => 'Quote not found'], 404);
		}

		return response()->json(['quote' => $quote], 200);
	}

	public function destroy(Quote $quote)
	{
		$quote->delete();

		return response()->json(['message' => 'success'], 201);
	}
}
