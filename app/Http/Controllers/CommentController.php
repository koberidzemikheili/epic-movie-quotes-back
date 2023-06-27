<?php

namespace App\Http\Controllers;

use App\Events\NewComment;
use App\Events\NewNotification;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request)
	{
		$response = DB::transaction(function () use ($request) {
			$comment = Comment::create([
				'comment'  => $request->comment,
				'user_id'  => Auth::id(),
				'quote_id' => $request->quote_id,
			]);

			// Fetch the quote
			$quote = Quote::with(['comments.user', 'likes', 'user', 'movie'])
				->where('id', $request->quote_id)
				->first();

			// Check if the user is commenting on their own quote
			if ($comment->user_id != $request->quote_userid) {
				// Create the notification
				$notification = new Notification;
				$notification->actor_id = Auth::id(); // Assuming this should be the same as user_id from the comment
				$notification->receiver_id = $request->quote_userid; // You'll need to set this to the ID of the user to be notified
				$notification->quote_id = $request->quote_id;
				$notification->action = 'comment'; // Assuming this is the correct action

				$notification->save();

				// Fire the NewNotification event
				event(new NewNotification($notification));
			}

			// Fire the NewComment event
			event(new NewComment($quote));

			return ['message' => 'successful'];
		});

		return response()->json($response, 201);
	}
}
