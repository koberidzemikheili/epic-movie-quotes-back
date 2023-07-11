<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyNewEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'username',
		'email',
		'password',
		'google_id',
		'profile_pictures',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password'          => 'hashed',
	];

	public function sendEmailVerificationNotification(): void
	{
		$this->notify(new CustomVerifyEmail());
	}

	public function sendPasswordResetNotification($token): void
	{
		$url = env('FRONT_END_URL') . '/new-password?token=' . $token . '&email=';
		$this->notify(new CustomResetPasswordNotification($url));
	}

	public function sendNewEmailVerificationNotification(): void
	{
		$this->notify(new CustomVerifyNewEmail);
	}

	public function markNewEmailAsVerified(): void
	{
		if ($this->new_email) {
			$this->email = $this->new_email;
			$this->new_email = null;
			$this->email_verified_at = now();
			$this->save();
		}
	}

	public function movies(): HasMany
	{
		return $this->hasMany(Movie::class);
	}

public function quotes(): HasMany
{
	return $this->hasMany(Quote::class);
}

public function comments(): HasMany
{
	return $this->hasMany(Comment::class);
}

public function notificationsReceived(): MorphMany
{
	return $this->morphMany(Notification::class, 'receiver');
}

public function notificationsSent(): MorphMany
{
	return $this->morphMany(Notification::class, 'actor');
}

public function likes(): BelongsToMany
{
	return $this->belongsToMany(Quote::class)->withPivot('id', 'user_id', 'quote_id');
}
}
