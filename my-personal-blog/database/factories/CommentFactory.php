<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'text' => $this->faker->sentences(2, true),
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
        ];
    }
}
