<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	public function actor()
	{
		return $this->morphTo();
	}

	public function receiver()
	{
		return $this->morphTo();
	}

	public function notifiable()
	{
		return $this->morphTo();
	}
}
