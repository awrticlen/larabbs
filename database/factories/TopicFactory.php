<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Topic>
 */
class TopicFactory extends Factory
{
    protected $model = Topic::class;

    public function definition(): array
    {
        $sentence = fake()->sentence();

        return [
            'title' => $sentence,
            'body' => fake()->text(),
            'excerpt' => $sentence,
            'user_id' => fake()->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
            'category_id' => fake()->randomElement([1, 2, 3, 4]),
        ];
    }
}
