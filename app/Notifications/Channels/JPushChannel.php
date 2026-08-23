<?php

namespace App\Notifications\Channels;

use App\Contracts\JPushNotification;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use JPush\Client;
use JPush\Exceptions\APIRequestException;
use Throwable;

class JPushChannel
{
    protected Client $client;

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
        $registrationId = self::registrationIdFor($notifiable);

        if ($registrationId === null) {
            return;
        }

        if (! $notification instanceof JPushNotification) {
            Log::warning('JPush channel received an unsupported notification.', [
                'notification' => $notification::class,
            ]);

            return;
        }

        try {
            $notification->toJPush($notifiable, $this->client->push())->send();
        } catch (APIRequestException $exception) {
            if ($exception->getCode() === 1003) {
                $this->forgetInvalidRegistrationId($notifiable);
            }

            $this->logDeliveryFailure($notifiable, $notification, $exception);
        } catch (Throwable $exception) {
            $this->logDeliveryFailure($notifiable, $notification, $exception);
        }
    }

    private function forgetInvalidRegistrationId(User $notifiable): void
    {
        try {
            $notifiable->forceFill(['registration_id' => null])->saveQuietly();
        } catch (Throwable $exception) {
            Log::warning('Unable to clear an invalid JPush registration ID.', [
                'user_id' => $notifiable->getKey(),
                'exception' => $exception,
            ]);
        }
    }

    private function logDeliveryFailure(
        User $notifiable,
        Notification $notification,
        Throwable $exception
    ): void {
        Log::warning('JPush notification delivery failed.', [
            'user_id' => $notifiable->getKey(),
            'notification' => $notification::class,
            'code' => $exception->getCode(),
            'exception' => $exception,
        ]);
    }
}
