<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dosen::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'nip' => $this->faker->unique()->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'alamat' => $this->faker->address(),
            'no_hp' => $this->faker->phoneNumber(),
        ];
    }
} 