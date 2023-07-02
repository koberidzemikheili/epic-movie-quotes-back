<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Events\UserLikedQuote;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
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
				$notification->action = 'like';
				$notification->notifiable()->associate(Quote::find($request->quote_id));
				$notification->actor()->associate(Auth::user());
				$notification->receiver()->associate(User::find($request->quote_userid));

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
