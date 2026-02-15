<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $fillable = [
        'reglement_id',
        'document_id',
        'numero_reglement',
        'mode_reglement',
        'montant',
        'date_reglement',
        'reference',
        'commentaire',
        'type_document',
    ];

     public function document()
{
    // Un règlement appartient à un seul document
    return $this->belongsTo(EnTeteDocument::class, 'document_id');
}
}

