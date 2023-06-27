<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
	public function store(Request $request)
	{
		Log::info('Store method called');

		$notification = new Notification();
		$notification->actor_id = $request->actor_id;
		$notification->receiver_id = $request->receiver_id;
		$notification->quote_id = $request->quote_id;
		$notification->action = $request->action;

		$notification->save();
		event(new NewNotification($notification));

		return response()->json($notification, 201);
	}
}
