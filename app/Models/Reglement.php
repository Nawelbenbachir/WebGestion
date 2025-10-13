<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $fillable = [
        'reglement_id',
        'document_id',
        'mode_reglement',
        'montant',
        'date_reglement',
        'reference',
        'commentaire',
        'type_docuement',
    ];

    public function document()
    {
        return $this->belongsTo(EnTeteDocument::class, 'document_id');
    }
}

