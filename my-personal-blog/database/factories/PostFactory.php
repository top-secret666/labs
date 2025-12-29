<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->optional()->imageUrl(),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
