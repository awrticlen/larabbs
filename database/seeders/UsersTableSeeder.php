<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(10)->create();

        $user = User::find(1);
        $user->name = 'Summer';
        $user->email = 'summer@example.com';
        $user->avatar = config('app.url') . '/images/default-avatar.svg';
        $user->save();
    }
}
