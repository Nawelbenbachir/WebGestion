<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneDocument extends Model
{
    protected $fillable = [
        'document_id',
        'produit_id',
        'description',
        'quantite',
        'prix_unitaire_ht',
        'taux_tva',
        'total_ttc',
        'commentaire',
    ];


    public function document()
    {
        return $this->belongsTo(EnTeteDocument::class, 'document_id');
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }
}
