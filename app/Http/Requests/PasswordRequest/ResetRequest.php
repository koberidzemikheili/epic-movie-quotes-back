<?php

namespace App\Http\Requests\PasswordRequest;

use Illuminate\Foundation\Http\FormRequest;

class ResetRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			'token'    => 'required',
			'email'    => 'required|email',
			'password' => 'required|min:3|confirmed',
		];
	}
}
