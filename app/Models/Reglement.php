<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $fillable = [
        'reglement_id',
        'numero_reglement',
        'mode_reglement',
        'montant',
        'date_reglement',
        'reference',
        'commentaire',
        'client_id',
        'societe_id',
        'exporte',
        'date_export',
    ];

     public function document()
    {
        // Un règlement appartient à un seul document
        return $this->belongsToMany(
            EnTeteDocument::class,
                'reglement_documents',
                'reglement_id',
                'document_id'
            )->withPivot('montant')->withTimestamps();
    
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}

