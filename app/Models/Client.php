<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_cli',
        'id_societe',
        'code_comptable',
        'nom',
        'prenom',
        'societe',
        'siret',
        'email',
        'portable1',
        'portable2',
        'telephone',
        'adresse1',
        'adresse2',
        'code_postal',
        'ville',
        'pays',
        'complement_adresse',
        'forme_juridique',
        'type',
        'iban',
        'reglement',
        'tva',
        'interlocuteur',
    ];
}

