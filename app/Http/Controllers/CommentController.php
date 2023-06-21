<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request)
	{
		$comment = new Comment();
		$comment->comment = $request->comment;
		$comment->user_id = Auth::id();
		$comment->quote_id = $request->quote_id;

		$comment->save();

		return response()->json(['message' => 'successfull'], 201);
	}
}
