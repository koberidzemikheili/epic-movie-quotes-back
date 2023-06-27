<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Events\UserLikedQuote;
use App\Models\Like;
use App\Models\Notification;
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

			// Create the notification
			$notification = new Notification();
			$notification->actor_id = Auth::id(); // Assuming this should be the same as user_id from the like
			$notification->receiver_id = $request->quote_userid; // You'll need to set this to the ID of the user to be notified
			$notification->quote_id = $request->quote_id;
			$notification->action = 'like'; // Assuming this is the correct action

			$notification->save();

			// Fire the events
			event(new UserLikedQuote($like));
			event(new NewNotification($notification));

			return ['message' => 'successful'];
		});

		return response()->json($response, 201);
	}

	public function destroy(Like $Like)
	{
		event(new UserLikedQuote($Like));
		$Like->delete();
		return response()->json(['message' => 'deleted successfully'], 200);
	}
}
