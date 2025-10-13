<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneDocument extends Model
{
    protected $fillable = [
        'document_id',
        'description',
        'quantite',
        'prix_unitaire',
        'tva',
        'total_ht',
        'total_ttc',
    ];

    public function document()
    {
        return $this->belongsTo(EnTeteDocument::class, 'document_id');
    }
}
