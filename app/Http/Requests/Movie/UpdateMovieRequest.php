<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			'name.en'           => 'required|string|min:3|max:70',
			'name.ka'           => 'required|string|min:3|max:70',
			'year'              => 'required|digits:4',
			'director.en'       => 'required|string|min:3|max:25',
			'director.ka'       => 'required|string|min:3|max:25',
			'description.en'    => 'required|string|min:3|max:255',
			'description.ka'    => 'required|string|min:3|max:255',
			'movie_image'       => 'nullable|image',
			'genres'            => 'required|array',
		];
	}
}
