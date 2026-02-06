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
        return [
        'societe_id'    => Societe::factory(), // Crée une société si on ne lui en donne pas
        'client_id'     => Client::factory(),
        'type_document' => 'D',
        'code_document' => 'TEMP-' . uniqid(), // Un code temporaire, écrasé par le seeder
        'date_document' => now(),
        'total_ht'      => 0,
        'total_tva'     => 0,
        'total_ttc'     => 0,
        'solde'         => 0,
        'statut'        => 'brouillon',
    ];
        // Récupérer un client et une société existants
//     $client = Client::inRandomOrder()->first();
//     $societe = Societe::inRandomOrder()->first();
//    $type = $this->faker->randomElement(['D', 'F', 'A']);
//         $annee = Carbon::now()->year;
//         $mois = Carbon::now()->format('m');
//         $nombreAleatoire = str_pad(rand(1, 999), 4, '0', STR_PAD_LEFT);

//         // On vérifie le format de la société pour générer un code compatible
//         if ($societe->format_numero_document === 'mensuel') {
//             $code = "{$type}-{$annee}-{$mois}-{$nombreAleatoire}";
//         } else {
//             $code = "{$type}-{$annee}-{$nombreAleatoire}";
//         }

//     return [
//         'societe_id' => $societe->id,
//         'client_id' => $client->id,
        
//         'type_document' => $type,

//         'date_document' => $this->faker->dateTimeBetween('-1 year', 'now'),
//         'code_document' => $code,
        
        
//         // Copie des infos client
//         'client_nom' => $client->nom, 
//         'adresse' => $client->adresse, 
//         'telephone' => $client->telephone, 
//         'email' => $client->email, 
        
//         'date_echeance' => $this->faker->dateTimeBetween('now', '+3 months'),
//         'commentaire' => $this->faker->sentence(),
//         'statut' =>  rand(0, 1) ? 'paye' : 'envoye',
        
//         // Initialisation des totaux à 0, ils seront calculés/mis à jour dans le Seeder
//         'total_ht' => 0.00,
//         'total_tva' => 0.00,
//         'total_ttc' => 0.00,
//         'solde' => 0.00,
//     ];

    }
}
