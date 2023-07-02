<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
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
			'title'       => $this->getTranslations('title'),
			'movie_id'    => $this->movie_id,
			'user_id'     => $this->user_id,
			'quote_image' => $this->quote_image,
			'created_at'  => $this->created_at,
			'user'        => new UserResource($this->whenLoaded('user')),
			'movie'       => new MovieResource($this->whenLoaded('movie')),
			'comments'    => CommentResource::collection($this->whenLoaded('comments')),
			'likes'       => LikeResource::collection($this->whenLoaded('likes')),
		];
	}
}
