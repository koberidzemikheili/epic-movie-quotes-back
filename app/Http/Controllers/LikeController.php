<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Events\UserLikedQuote;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
	public function store(Request $request)
	{
		$response = DB::transaction(function () use ($request) {
			$like = new Like();
			$like->user_id = Auth::id();
			$like->quote_id = $request->quote_id;

			$like->save();

			// Check if the user is liking their own quote
			if ($like->user_id != $request->quote_userid) {
				// Create the notification
				$notification = new Notification();
				$notification->actor_id = Auth::id();
				$notification->receiver_id = $request->quote_userid;
				$notification->quote_id = $request->quote_id;
				$notification->action = 'like';

				$notification->save();

				// Fire the NewNotification event
				event(new NewNotification($notification));
			}

			// Now that the like is created, we get the updated quote.
			$quote = Quote::with(['comments.user', 'likes', 'user', 'movie'])->find($like->quote_id);

			// Fire the UserLikedQuote event
			event(new UserLikedQuote($quote));

			return ['message' => 'successful'];
		});

		return response()->json($response, 201);
	}

public function destroy(Like $like)
{
	$like->delete();

	// Now that the like is deleted, we get the updated quote.
	$quote = Quote::with(['comments.user', 'likes', 'user', 'movie'])->find($like->quote_id);

	event(new UserLikedQuote($quote));

	return response()->json(['message' => 'deleted successfully'], 200);
}
}
