<?php

namespace App\Contracts;

use App\Models\User;
use JPush\PushPayload;

interface JPushNotification
{
    public function toJPush(User $notifiable, PushPayload $payload): PushPayload;
}