<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			'title.en'          => 'required|min:3|max:255',
			'title.ka'          => 'required|min:3|max:255',
			'movie_id'          => 'required',
			'quote_image'       => 'nullable|image',
		];
	}
}
