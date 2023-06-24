<?php

namespace App\Http\Controllers;

use App\Events\NewComment;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request)
	{
		$comment = Comment::create([
			'comment'  => $request->comment,
			'user_id'  => Auth::id(),
			'quote_id' => $request->quote_id,
		]);

		event(new NewComment($comment));

		return response()->json(['message' => 'successfull'], 201);
	}
}
