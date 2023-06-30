<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	use HasFactory;

	use HasTranslations;

	protected $guarded = ['id'];

	public $translatable = ['name', 'director', 'description'];

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	public function genres()
	{
		return $this->belongsToMany(Genre::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}