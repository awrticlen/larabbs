<?php

namespace App\Notifications\Channels;

use App\Models\User;
use JPush\Client;
use Illuminate\Notifications\Notification;

class JPushChannel
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public static function registrationIdFor(User $notifiable): ?string
    {
        $registrationId = $notifiable->registration_id ?? null;

        if (! is_string($registrationId)) {
            return null;
        }

        $registrationId = trim($registrationId);

        return $registrationId === '' ? null : $registrationId;
    }

    public function send(User $notifiable, Notification $notification): void
    {
        if (self::registrationIdFor($notifiable) === null) {
            return;
        }

        $notification->toJPush($notifiable, $this->client->push())->send();
    }
}
