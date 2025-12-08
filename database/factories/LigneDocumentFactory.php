<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produit;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LigneDocument>
 */
class LigneDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produit = Produit::inRandomOrder()->first();
    $quantite = $this->faker->numberBetween(1, 10);
    $prixUnitaireHt = $produit->prix_vente_ht ?? $this->faker->randomFloat(2, 10, 500);
    $tauxTva = 20.00; 

    $totalHtLigne = $prixUnitaireHt * $quantite;
    $totalTvaLigne = $totalHtLigne * ($tauxTva / 100);
    $totalTtcLigne = $totalHtLigne + $totalTvaLigne;

    return [
        // 'document_id' sera rempli par le seeder
        'produit_id' => $produit->id,
        'description' => $produit->description?? 'Description générique pour le produit ID: ' . $produit->id,
        'prix_unitaire_ht' => round($prixUnitaireHt, 2),
        'taux_tva' => $tauxTva,
        'quantite' => $quantite,
        'total_ttc' => round($totalTtcLigne, 2), // Le calcul de la ligne peut rester ici
        'commentaire' => $this->faker->optional()->sentence(),
    ];
    }
}
