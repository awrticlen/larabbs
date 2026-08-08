<?php

namespace Tests\Traits;

use App\Models\User;
use Laravel\Passport\Passport;

trait ActingJWTUser
{
    public function JWTActingAs(User $user): static
    {
        Passport::actingAs($user);

        return $this;
    }
}
