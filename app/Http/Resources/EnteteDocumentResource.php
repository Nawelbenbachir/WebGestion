<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnteteDocumentResource extends JsonResource
{
    /**
     * Transforme la ressource en tableau JSON.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type_document, // "D", "F", "A"
            'numero' => $this->code_document ?? $this->numero_document,
            'date' => $this->date_document,
            'date_echeance'=>$this->date_echeance,
            'date_validite'=>$this->date_validite,
            'solde'=>$this->solde,
            'total_ht' => $this->total_ht,
            'total_ttc' => $this->total_ttc,
            
            // Informations sur le client
            'client' => [
                'id' => $this->client_id,
                'nom' => $this->client->nom ?? 'Client inconnu',
            ],

            // Informations sur la société émettrice
            'societe' => [
                'id' => $this->societe_id,
                'nom' => $this->societe->nom ?? 'Société inconnue',
            ],

            
            'lignes' => $this->whenLoaded('lignes'),
        ];
    }
}