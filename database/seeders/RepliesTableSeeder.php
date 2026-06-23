<?php

namespace Database\Seeders;

use App\Models\Reply;
use Illuminate\Database\Seeder;

class RepliesTableSeeder extends Seeder
{
    public function run(): void
    {
        Reply::factory()->count(1000)->create();
    }
}
