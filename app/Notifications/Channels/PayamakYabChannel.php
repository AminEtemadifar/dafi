<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Log;
use SoapClient;
use Illuminate\Notifications\Notification;

class PayamakYabChannel
{
    public function send($notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toPayamakYab')) {
            return;
        }

        $message = $notification->toPayamakYab($notifiable);
        if (! $message || ! isset($message['to'], $message['text'])) {
            return;
        }

        $config = config('sms.sms.payamakYab');

        try {
            $client = new SoapClient($config['url']);
            $params = [
                'username' => $config['username'],
                'password'     => $config['password'],
                'text'     => $message['text'],
                'to'  => [$message['to']],
                'from' => $config['number'],
                'isflash' => true,
            ];

            $res = $client->SendSms($params);

            Log::info(json_encode($res));
        } catch (\Throwable $e) {
           Log::error($e->getMessage());
        }
    }
}


