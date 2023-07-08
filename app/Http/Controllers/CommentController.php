<?php

namespace App\Http\Controllers;

use App\Events\NewComment;
use App\Events\NewNotification;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request): JsonResponse
	{
		$response = DB::transaction(function () use ($request) {
			$validated = $request->validated();
			$comment = Comment::create([
				'comment'  => $validated['comment'],
				'user_id'  => Auth::id(),
				'quote_id' => $validated['quote_id'],
			]);

			$quote = Quote::with(['comments.user', 'likes', 'user', 'movie'])
				->where('id', $validated['quote_id'])
				->first();

			if ($comment->user_id != $validated['quote_userid']) {
				$notification = new Notification();
				$notification->action = 'comment';
				$notification->notifiable()->associate(Quote::find($validated['quote_id']));
				$notification->actor()->associate(Auth::user());
				$notification->receiver()->associate(User::find($validated['quote_userid']));

				$notification->save();

				event(new NewNotification($notification));
			}

			event(new NewComment($quote));

			return ['message' => 'successful'];
		});

		return response()->json($response, 201);
	}
}
