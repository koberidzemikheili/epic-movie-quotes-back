<?php

namespace App\Http\Requests\EditUser;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			'username'           => 'min:6|max:255',
			'profile_picture'    => 'nullable|image',
			'email'              => 'email|max:255|unique:users,email',
			'password'           => 'max:255|min:3',
		];
	}
}
