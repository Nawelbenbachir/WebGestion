<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_societe' => rand(1, 3),
            'code_comptable' => 'COMPT' . rand(1000, 9999),
            'code_cli' => strtoupper($this->faker->lexify('CLT-????')),
            'societe' => $this->faker->company,
            'type' => $this->faker->randomElement(['particulier', 'artisan', 'entreprise']),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'adresse1' => $this->faker->address(),
            'adresse2' => $this->faker->address(),
            'complement_adresse' => $this->faker->word(),
            'ville' => $this->faker->city(),
            'code_postal' => $this->faker->postcode(),
            'pays' => 'France',
        ];
    }
}