<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatistiqueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   public function toArray(Request $request): array
    {
        // Gérer le cas où $this->resource est null ou ne contient pas de données
        if (is_null($this->total_quantite)) {
            return [
                'id_produit' => $this->id_produit ?? null,
                'code_produit' => $this->code_produit ?? null,
                'description_produit' => $this->description_produit ?? null,
                'mois' => $this->mois ?? null,
                'annee' => $this->annee ?? null,
                'total_ht' => 0.00,
                'total_tva' => 0.00,
                'total_ttc' => 0.00,
                'quantite_vendue' => 0,
            ];
        }

        return [
            'id_produit' => $this->id_produit ?? null,
            'code_produit' => $this->code_produit ?? null,
            'description_produit' => $this->description_produit ?? null,
            'mois' => $this->mois ?? null,
            'annee' => $this->annee ?? null,
            
            // Formatage des statistiques
            'total_ht' => round((float)$this->total_ht, 2),
            'total_tva' => round((float)$this->total_tva, 2),
            'total_ttc' => round((float)$this->total_ttc, 2),
            'quantite_vendue' => (int)$this->total_quantite,
        ];
    }
}
