<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Helpers\Constant;
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
    public function definition()
    {
        $user = User::firstOrFail();
        $category = Category::factory()->create(['name' => 'Education', 'user_id' => $user->id]);
        return [
            'title' => $this->faker->name(),
            'content' => $this->faker->text(),
            'status' => $this->faker->randomElement([Constant::TRUE_CONDITION, Constant::FALSE_CONDITION]),
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];
    }
}