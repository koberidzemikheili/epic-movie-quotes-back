<?php

namespace App\Http\Controllers;

use App\Events\NewComment;
use App\Events\NewNotification;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
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

			// Create the notification
			$notification = new Notification;
			$notification->actor_id = Auth::id(); // Assuming this should be the same as user_id from the comment
			$notification->receiver_id = $request->quote_userid; // You'll need to set this to the ID of the user to be notified
			$notification->quote_id = $request->quote_id;
			$notification->action = 'comment'; // Assuming this is the correct action

			$notification->save();

			// Fire the events
			event(new NewComment($comment));
			event(new NewNotification($notification));

			return ['message' => 'successful'];
		});

		return response()->json($response, 201);
	}
}
