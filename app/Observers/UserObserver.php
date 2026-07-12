<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function saving(User $user): void
    {
        if (blank($user->avatar)) {
            $user->avatar = rtrim(config('app.url'), '/') . '/images/default-avatar.svg';
        }
    }
}