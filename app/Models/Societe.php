<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    protected $fillable = [
        'code_societe',
        'nom_societe',
        'adresse1',
        'adresse2',
        'complement_adresse',
        'code_postal',
        'ville',
        'pays',
        'telephone',
        'email',
        'siret',
        'iban',
        'swift',
        'tva',
        'logo',
    ];
        public function documents()
    {
        return $this->hasMany(EnTeteDocument::class, 'code_societe', 'code_societe');
    }
}
