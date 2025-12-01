<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 
use Illuminate\Support\Str;

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
        // Récupérer la liste des IDs des utilisateurs existants
        $userIds = User::pluck('id');

        //  Vérifie explicitement si la liste d'IDs est vide.
        // Si elle est vide (ce qui arrive si UserSeeder a échoué ou n'a pas été appelé),
        // on crée un utilisateur temporaire pour le lier à cette société.
        if ($userIds->isEmpty()) {
            $proprietaireId = User::factory()->create()->id;
        } else {
            // Sinon, on prend un ID d'utilisateur existant au hasard.
            $proprietaireId = $userIds->random();
        }

        return [
            'code_societe' => Str::upper(Str::random(3)) . $this->faker->randomNumber(3, true),
            'nom_societe' => $this->faker->company(),
            'siret' => $this->faker->unique()->numerify('##############'), 
            'email' => $this->faker->unique()->companyEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'adresse1' => $this->faker->streetAddress(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city(),
            'pays' => $this->faker->country(),
            'iban' => $this->faker->iban(),
            'tva' => $this->faker->vat(),
            'logo' => null,
            
            'proprietaire_id' => $proprietaireId,
        ];
    }
}