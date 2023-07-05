<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'         => $this->pivot->id,
			'quote_id'   => $this->pivot->quote_id,
			'user_id'    => $this->pivot->user_id,
		];
	}
}
