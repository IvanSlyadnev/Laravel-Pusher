<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tags = collect(['php', 'ruby', 'java', 'javascript', 'bash'])
            ->random(2)
            ->values()
            ->all();
        return [
            'title' => fake()->sentence(),
            'body' => fake()->text(),
            'tags' => $tags,
        ];
    }
}
