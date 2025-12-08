<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Societe;
use Carbon\Carbon;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EnTeteDocument>
 */
class EnTeteDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Récupérer un client et une société existants
    $client = Client::inRandomOrder()->first();
    $societe = Societe::inRandomOrder()->first();
    $type = 'F';

    return [
        'societe_id' => $societe->id,
        'client_id' => $client->id,
        
        // Champs de la migration
        'code_document' => $type . '-' . Carbon::now()->year . '-' . str_pad(rand(1, 99999), 6, '0', STR_PAD_LEFT),
        'type_document' => $type,
        'date_document' => $this->faker->dateTimeBetween('-1 year', 'now'),
        
        // Copie des infos client
        'client_nom' => $client->nom, 
        'adresse' => $client->adresse, 
        'telephone' => $client->telephone, 
        'email' => $client->email, 
        
        'date_echeance' => $this->faker->dateTimeBetween('now', '+3 months'),
        'commentaire' => $this->faker->sentence(),
        'statut' =>  rand(0, 1) ? 'paye' : 'envoye',
        
        // Initialisation des totaux à 0, ils seront calculés/mis à jour dans le Seeder
        'total_ht' => 0.00,
        'total_tva' => 0.00,
        'total_ttc' => 0.00,
        'solde' => 0.00,
    ];
    }
}
