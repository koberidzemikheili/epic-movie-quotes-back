<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomVerifyNewEmail extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct()
	{
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via($notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail($notifiable): MailMessage
	{
		$username = $notifiable->username;

		$verificationUrl = $this->verificationUrl($notifiable);

		return (new MailMessage)->view(
			'emails.new-email-verification',
			['verificationUrl' => $verificationUrl, 'username' => $username]
		);
	}

	/**
	 * Get the verification URL for the notifiable.
	 */
	protected function verificationUrl($notifiable): string
	{
		return URL::temporarySignedRoute(
			'verification.verify_new_email',
			now()->addMinutes(60),
			['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->new_email)]
		);
	}
}
