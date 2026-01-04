<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LigneDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_entete_document' => $this->id_entete_document,
            'id_produit' => $this->id_produit,
            'description' => $this->description,
            'quantite' => $this->quantite,
            'prix_unitaire_ht' => $this->prix_unitaire_ht,
            'taux_tva' => $this->taux_tva,
            'total_ttc' => $this->total_ttc,
            'commentaire' => $this->commentaire,
        ];
    }
}
