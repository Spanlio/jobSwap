<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
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
        return [
            'user_id' => User::factory(),
            'current_job_title' => fake()->jobTitle(),
            'desired_job_title' => fake()->jobTitle(),
            'licenses' => fake()->optional()->sentence(),
            'years_experience' => fake()->numberBetween(0, 25),
            'region' => fake()->randomElement(array_keys(config('jobswap.regions'))),
            'availability' => fake()->randomElement(array_keys(config('jobswap.availability'))),
            'employer_email' => fake()->companyEmail(),
            'employer_name' => fake()->company(),
            'status' => Post::STATUS_ACTIVE,
            'expires_at' => now()->addDays(config('jobswap.post_lifetime_days')),
        ];
    }
}
