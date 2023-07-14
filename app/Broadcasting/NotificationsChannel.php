<?php

namespace App\Broadcasting;

use Illuminate\Contracts\Auth\Authenticatable;

class NotificationsChannel
{
	public function join(Authenticatable $user, $id)
	{
		return (int) $user->id === (int) $id;
	}
}
