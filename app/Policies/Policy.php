<?php

namespace App\Policies;

use App\Models\User;

class Policy
{
    protected function isSuperAdmin(User $user): bool
    {
        return false;
    }
}
