<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Http\Requests\Quote\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function getQuotes(Request $request)
	{
		$search = $request->searchBy;
		$query = Quote::query();

		if ($search) {
			if ($search[0] === '#') {
				$search = ltrim($search, '#');
				$query->where('title->en', 'like', '%' . $search . '%')
					  ->orWhere('title->ka', 'like', '%' . $search . '%');
			} elseif ($search[0] === '@') {
				$search = ltrim($search, '@');
				$query->whereHas('movie', function ($query) use ($search) {
					$query->where('name->en', 'like', '%' . $search . '%')
						  ->orWhere('name->ka', 'like', '%' . $search . '%');
				});
			} else {
				$query->where('title->en', 'like', '%' . $search . '%')
					  ->orWhere('title->ka', 'like', '%' . $search . '%')
					  ->orWhereHas('movie', function ($query) use ($search) {
					  	$query->where('name->en', 'like', '%' . $search . '%')
					  		->orWhere('name->ka', 'like', '%' . $search . '%');
					  });
			}
		}

		$quotes = $query->with(['comments' => function ($query) {
			$query->orderBy('created_at', 'desc');
		}, 'comments.user', 'likes', 'user', 'movie'])
		->orderBy('created_at', 'desc')
		->paginate(5);

		return QuoteResource::collection($quotes);
	}

	public function show(Quote $quote): JsonResponse
	{
		$quote->load(['comments' => function ($query) {
			$query->orderBy('created_at', 'desc');
		}, 'comments.user', 'likes', 'user', 'movie']);

		if (!$quote) {
			return response()->json(['error' => 'Quote not found'], 404);
		}

		return response()->json(['quote' => new QuoteResource($quote)]);
	}

	public function store(StoreQuoteRequest $request): JsonResponse
	{
		$validated = $request->validated();

		$data = array_merge($validated, [
			'user_id'     => Auth::id(),
			'quote_image' => $request->file('quote_image')->store('quote_images'),
		]);

		Quote::create($data);

		return response()->json(['message' => 'Quote created successfully'], 201);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$this->authorize('update', $quote);

		$validated = $request->validated();

		if ($request->hasFile('quote_image')) {
			Storage::delete($quote->quote_image);
			$validated['quote_image'] = $request->file('quote_image')->store('quote_images');
		} else {
			unset($validated['quote_image']);
		}

		$validated['user_id'] = Auth::id();

		$quote->update($validated);

		return response()->json(['message' => 'success'], 200);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$this->authorize('delete', $quote);
		$quote->delete();

		return response()->json(['message' => 'success'], 201);
	}
}
