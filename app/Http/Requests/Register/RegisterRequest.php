<?php

namespace App\Http\Requests\Register;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			'username' => 'required|max:255|min:3|unique:users,username',
			'email'    => 'required|email|max:255|unique:users,email',
			'password' => 'required|max:255|min:3|confirmed',
		];
	}
}
