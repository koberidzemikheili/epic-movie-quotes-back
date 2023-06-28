<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'                    => $this->id,
			'google_id'             => $this->google_id,
			'username'              => $this->username,
			'email'                 => $this->email,
			'profile_pictures'      => $this->profile_pictures,
			'created_at'            => $this->created_at,
			'notificationsReceived' => NotificationResource::collection($this->whenLoaded('notificationsReceived')),
		];
	}
}
