<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
			'name'        => $this->getTranslations('name'),
			'year'        => $this->year,
			'director'    => $this->getTranslations('director'),
			'description' => $this->getTranslations('description'),
			'user_id'     => $this->user_id,
			'movie_image' => $this->movie_image,
			'created_at'  => $this->created_at,
			'genres'      => GenreResource::collection($this->whenLoaded('genres')),
			'quotes'      => QuoteResource::collection($this->whenLoaded('quotes')),
		];
	}
}
