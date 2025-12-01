<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Societe extends Model
{
    use HasFactory;
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
        'proprietaire_id',
        
    ];
        public function documents()
    {
        return $this->hasMany(EnTeteDocument::class, 'code_societe', 'code_societe');
    }
}
