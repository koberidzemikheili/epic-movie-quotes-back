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
		$url = 'http://localhost:5173/new-password?token=' . $token . '&email=';
		$this->notify(new CustomResetPasswordNotification($url));
	}

	public function movies()
	{
		return $this->hasMany(Movie::class);
	}

public function quotes()
{
	return $this->hasMany(Quote::class);
}

public function comments()
{
	return $this->hasMany(Comment::class);
}

public function notificationsReceived()
{
	return $this->hasMany(Notification::class, 'receiver_id');
}

public function notificationsSent()
{
	return $this->hasMany(Notification::class, 'actor_id');
}
}
