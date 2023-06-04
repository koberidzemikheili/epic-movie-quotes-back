<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPassword
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct($token)
	{
		$this->token = $token;
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
		$url = $this->token . $notifiable->getEmailForPasswordReset();

		return (new MailMessage)->view(
			'emails.resetpassword',
			['resetUrl' => $url]
		);
	}
}
