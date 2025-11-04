<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Societe>
 */
class SocieteFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       

        return [
            'code_societe' => 'SOC' . rand(100, 999),
            'nom_societe' => $this->faker->company,
            'siret' => $this->faker->unique()->numerify('##############'),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'adresse1' => $this->faker->address(),
            'adresse2' => $this->faker->address(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city(),
            'complement_adresse' => $this->faker->word(),
            'iban' => $this->faker->iban(),
            //'swift' => $this->faker->swiftBic(),
            'tva'=> $this->faker->unique()->numerify('FR###########'),
            'pays' => 'France',
        ];
    }
}
