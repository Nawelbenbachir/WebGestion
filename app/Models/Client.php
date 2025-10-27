<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_cli',
        'societe',
        'siret',
        'email',
        'portable1',
        'portable2',
        'telephone',
        'adresse1',
        'adresse2',
        'complement_adresse',
        'forme_juridique',
        'type',
        'iban',
        'reglement',
        'echeance_jour',
        'tva',
        'interlocuteur',
    ];
}

