<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserOwnershipPolicy
{
	use HandlesAuthorization;

	public function update(User $user, $item)
	{
		return $user->id === $item->user_id;
	}

	public function delete(User $user, $item)
	{
		return $user->id === $item->user_id;
	}
}
