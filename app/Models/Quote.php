<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
	use HasFactory;

	use HasTranslations;

	public $translatable = ['title'];

	protected $guarded = ['id'];

	public function movie(): BelongsTo
	{
		return $this->belongsTo(Movie::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->belongsToMany(User::class)->withPivot('id', 'user_id', 'quote_id');
	}

	public function notifications()
	{
		return $this->morphMany(Notification::class, 'notifiable');
	}
}
