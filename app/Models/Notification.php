<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
	use HasFactory;

	public function actor(): MorphTo
	{
		return $this->morphTo();
	}

	public function receiver(): MorphTo
	{
		return $this->morphTo();
	}

	public function notifiable(): MorphTo
	{
		return $this->morphTo();
	}
}
