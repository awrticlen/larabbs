<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Topic>
 */
class TopicFactory extends Factory
{
    protected $model = Topic::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'body' => fake()->paragraphs(3, true),
            'user_id' => User::factory(),
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? 1,
            'reply_count' => fake()->numberBetween(0, 100),
            'view_count' => fake()->numberBetween(0, 1000),
            'last_reply_user_id' => 0,
            'order' => 0,
            'excerpt' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
        ];
    }
}
