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
        
    }
}
