<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CardList>
 */
class CardListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' =>User::factory(),
            'board_id' =>Board::factory(),
            'name' => $this->faker->sentence,
        ];
    }
}
