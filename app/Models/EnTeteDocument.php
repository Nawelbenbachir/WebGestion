<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnTeteDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'societe_id',
        'code_document',
        'type_document', 
        'date_document',
        'total_ht',
        'total_tva',
        'total_ttc',
        'solde',
        'client_id',
        'client_nom',
        'adresse',
        'code_postal',
        'ville',
        'telephone',
        'email',
        'date_echeance',
        'date_validite',
        'commentaire',
        'statut',

    ];
    protected $casts = [
    'total_ht'  => 'float',
    'total_tva' => 'float',
    'total_ttc' => 'float',
    ];
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneDocument::class, 'document_id');
    }
    public function reglements() {
    return $this->hasMany(Reglement::class, 'document_id');
}
}
