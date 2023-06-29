<?php

namespace App\Events;

use App\Models\Quote;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $quote;

	public function __construct(Quote $quote)
	{
		$this->quote = $quote;
	}

	public function broadcastOn()
	{
		return new Channel('comments.' . $this->quote->id);
	}

	public function broadcastWith()
	{
		return ['quote' => $this->quote];
	}
}
