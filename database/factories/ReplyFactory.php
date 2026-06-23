<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition(): array
    {
        return [
            'content' => fake()->sentence(),
            'topic_id' => fake()->numberBetween(1, 100),
            'user_id' => fake()->numberBetween(1, 10),
        ];
    }
}
