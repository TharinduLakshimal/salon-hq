<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => fn (): int => Category::firstOrCreate(
                ['slug' => Str::slug('Hair Stylists')],
                ['name' => 'Hair Stylists', 'description' => 'Hair Stylists department at Salon HQ.']
            )->id,
            'name' => fake()->words(3, true),
            'target_gender' => fake()->randomElement(['ladies', 'gents', 'both']),
            'price' => fake()->randomElement([600, 1500, 2500, 4000, 10000]),
            'duration_minutes' => fake()->randomElement([20, 30, 45, 60, 90]),
            'is_package' => false,
        ];
    }

    /**
     * Indicate that the service is a bundled salon package.
     */
    public function package(): static
    {
        return $this->state(fn (): array => [
            'is_package' => true,
        ]);
    }
}
