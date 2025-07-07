<?php

namespace Database\Factories;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role'          => fake()->randomElement(UserRoles::all()),
            'first_name'    => fake()->firstName(),
            'last_name'     => fake()->lastName(),
            'email'         => fake()->unique()->safeEmail(),
            'phone'         => fake()->phoneNumber(),
            'password'      => bcrypt('password123'),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => UserRoles::ADMIN->value,
            ];
        });
    }

    public function writer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => UserRoles::WRITER->value,
            ];
        });
    }

    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => UserRoles::USER->value,
            ];
        });
    }
}
