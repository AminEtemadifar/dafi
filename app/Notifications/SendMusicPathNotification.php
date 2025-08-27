<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\PayamakYabChannel;

class SendMusicPathNotification extends Notification
{
	use Queueable;

	public function __construct(public string $mobile, public string $musicUrl, public string $name)
	{
	}

	public function via($notifiable): array
	{
		return [PayamakYabChannel::class];
	}

	public function toPayamakYab($notifiable): array
	{
        $text = "لینک موزیک شما برای نام {$this->name}: {$this->musicUrl}\nلغو11";
		return [
			'to' => $this->mobile,
			'text' => $text,
		];
	}
}
