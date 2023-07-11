<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
	public function markAsSeen(Notification $notification): JsonResponse
	{
		$notification->is_seen = true;
		$notification->save();

		return response()->json(['message' => 'Notification marked as seen']);
	}

	public function markAllAsSeen(Request $request): JsonResponse
	{
		$userId = $request->input('userId');
		Notification::where('receiver_id', $userId)->update(['is_seen' => true]);

		return response()->json(['message' => $userId]);
	}
}
