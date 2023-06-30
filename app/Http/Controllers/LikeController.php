<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Events\UserLikedQuote;
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
			$quote = Quote::find($request->quote_id);
			$quote->likes()->attach(Auth::id());

			if (Auth::id() != $request->quote_userid) {
				$notification = new Notification();
				$notification->actor_id = Auth::id();
				$notification->receiver_id = $request->quote_userid;
				$notification->quote_id = $request->quote_id;
				$notification->action = 'like';

				$notification->save();

				event(new NewNotification($notification));
			}

			$quote = Quote::with(['comments.user', 'likes', 'user', 'movie'])->find($request->quote_id);

			event(new UserLikedQuote($quote));

			return ['message' => 'successful'];
		});

		return response()->json($response, 201);
	}

public function destroy(Request $request)
{
	$response = DB::transaction(function () use ($request) {
		$quote = Quote::find($request->quote_id);
		$quote->likes()->detach(Auth::id());

		$quote = Quote::with(['comments.user', 'likes', 'user', 'movie'])->find($request->quote_id);

		event(new UserLikedQuote($quote));

		return ['message' => 'deleted successfully'];
	});

	return response()->json($response, 200);
}
}
