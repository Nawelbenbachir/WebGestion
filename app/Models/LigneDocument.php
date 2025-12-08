<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LigneDocument extends Model
{
    use HasFactory;
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
    public function reglements()
    {
    
        return $this->hasMany(Reglement::class, 'document_id'); 
    }
}
