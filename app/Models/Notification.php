<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	public function actor()
	{
		return $this->belongsTo(User::class, 'actor_id');
	}

	public function receiver()
	{
		return $this->belongsTo(User::class, 'receiver_id');
	}

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}
}
