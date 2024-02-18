<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->text(),
            'content' => fake()->text(),
            'user_id' => \App\Models\User::all()->random()->id,
        ];
    }

    public function complete(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => true,
            ];
        });
    }
}
