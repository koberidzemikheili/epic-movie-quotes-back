<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
	public function store(Request $request)
	{
		$like = new Like();
		$like->user_id = Auth::id();
		$like->quote_id = $request->quote_id;

		$like->save();

		return response()->json(['message' => 'successfull'], 201);
	}

	public function destroy(Like $Like)
	{
		$Like->delete();

		return response()->json(['message' => 'deleted successfully'], 200);
	}
}
