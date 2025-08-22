<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\PayamakYabChannel;

class SendOtpNotification extends Notification
{
    use Queueable;

    public function __construct(public string $mobile, public string $otp) {}

    public function via($notifiable): array
    {
        return [PayamakYabChannel::class];
    }

    public function toPayamakYab($notifiable): array
    {
        $text = "کد تایید شما: {$this->otp}  لغو11";
        return [
            'to' => $this->mobile,
            'text' => $text,
        ];
    }
}
