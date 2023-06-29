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

			$quote = Quote::with(['comments.user', 'likes', 'user', 'movie'])
				->where('id', $request->quote_id)
				->first();

			if ($comment->user_id != $request->quote_userid) {
				$notification = new Notification;
				$notification->actor_id = Auth::id();
				$notification->receiver_id = $request->quote_userid;
				$notification->quote_id = $request->quote_id;
				$notification->action = 'comment';

				$notification->save();

				event(new NewNotification($notification));
			}

			event(new NewComment($quote));

			return ['message' => 'successful'];
		});

		return response()->json($response, 201);
	}
}
