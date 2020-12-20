<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class GameFactory
 * @package Database\Factories
 */
class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'host_id'     => $this->faker->randomDigitNotNull,
            'guest_id'    => $this->faker->randomDigitNotNull,
            'week_number' => $this->faker->randomDigitNotNull,
        ];
    }
}
