<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;


class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_societe' => rand(1, 3),
            'code_comptable' => 'COMPT' . rand(1000, 9999),
            'code_cli' => strtoupper($this->faker->lexify('CLT-????')),
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'societe' => $this->faker->company,
            'type' => $this->faker->randomElement(['particulier', 'artisan', 'entreprise']),
            'forme_juridique' => $this->faker->randomElement(['SAS', 'SARL', 'EURL', 'SA']),
            'siret' => $this->faker->unique()->numerify('##############'),
            'reglement' => $this->faker->randomElement(['Virement', 'Chèque', 'Espèces']),
            
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'adresse1'           => $this->faker->streetAddress(), 
            'adresse2'           => $this->faker->secondaryAddress(), 
            'complement_adresse' => 'Bâtiment ' . $this->faker->randomLetter(),
            'ville' => $this->faker->city(),
            'code_postal' => $this->faker->postcode(),
            'pays' => 'France',
        ];
    }
}