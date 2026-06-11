<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic): bool
    {
        if ($topic->user_id == $user->id) {
            return true;
        }

        return false;
    }

    public function destroy(User $user, Topic $topic): bool
    {
        return true;
    }
}
