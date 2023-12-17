<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Board;
use App\Models\CardList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'board_id' => Board::factory(),
            'card_list_id' => CardList::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentences(3, true),
        ];
    }
}
