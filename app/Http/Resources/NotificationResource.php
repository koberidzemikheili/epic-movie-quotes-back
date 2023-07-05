<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'          => $this->id,
			'actor'       => new UserResource($this->whenLoaded('actor')),
			'receiver'    => new UserResource($this->whenLoaded('receiver')),
			'notifiable'  => new QuoteResource($this->whenLoaded('notifiable')),
			'action'      => $this->action,
			'is_seen'     => $this->is_seen,
			'created_at'  => $this->created_at,
		];
	}
}
