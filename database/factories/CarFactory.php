<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\Model;
use App\Models\User;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand' => $this->faker->word,
            'slug' => $this->faker->slug,
            'description' => $this->faker->text,
            'model_id' => Model::factory(),
            'user_id' => User::factory(),
        ];
    }
}
