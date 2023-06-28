<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Http\Requests\Quote\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function getQuotes()
	{
		$quotes = Quote::with(['comments' => function ($query) {
			$query->orderBy('created_at', 'desc');
		}, 'comments.user', 'likes', 'user', 'movie'])
		->orderBy('created_at', 'desc')
		->paginate(5);

		return response()->json([
			'quotes' => QuoteResource::collection($quotes),
		], 200);
	}

	public function index($id)
	{
		$quote = Quote::with('comments.user', 'likes', 'user', 'movie')->find($id);

		if (!$quote) {
			return response()->json(['error' => 'Quote not found'], 404);
		}

		return response()->json(['quote' => new QuoteResource($quote)]);
	}

	public function store(StoreQuoteRequest $request)
	{
		Quote::create([
			'title'       => $request->title,
			'movie_id'    => $request->movie_id,
			'quote_image' => $request->file('quote_image')->store('quote_images'),
			'user_id'     => Auth::id(),
		]);

		return response()->json(['message' => 'Quote created successfully'], 201);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote)
	{
		if ($request->hasFile('quote_image')) {
			Storage::delete($quote->quote_image);
			$quote->quote_image = $request->file('quote_image')->store('quote_images');
		}

		$quote->update([
			'title'       => ['en' => $request->title['en'], 'ka' => $request->title['ka']],
			'user_id'     => Auth::id(),
			'quote_image' => $quote->quote_image,
		]);

		return response()->json(['message' => 'success'], 200);
	}

	public function destroy(Quote $quote)
	{
		$quote->delete();

		return response()->json(['message' => 'success'], 201);
	}
}
