<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;

class CustomNewEmailVerificationRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
		];
	}

	/**
	 * Fulfill the new email verification request.
	 *
	 * @return void
	 */
	public function fulfill()
	{
		$userId = $this->route('id');
		$newEmail = $this->route('hash');

		$user = User::findOrFail($userId);

		if (hash_equals((string) $newEmail, sha1($user->new_email))) {
			$user->markNewEmailAsVerified();
			event(new Verified($user));
		}
	}
}
