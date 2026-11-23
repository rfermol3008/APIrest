<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Academy;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 week', '+3 months');

        return [
            'academy_id' => Academy::factory(),
            'name' => fake()->randomElement(['Laravel', 'PHP', 'Docker', 'Git', 'SQL'])
                . ' ' . fake()->randomElement(['básico', 'intermedio', 'avanzado']),
            'description' => fake()->sentence(),
            'start_date' => $start,
            'end_date' => (clone $start)->modify('+2 months'),
            'capacity' => fake()->numberBetween(10, 30),
        ];
    }
}
