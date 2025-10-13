<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'code_societe',
        'code_produit',
        'code_comptable',
        'description',
        'prix_ht',
        'tva',
        'qt_stock',
        'categorie',
    ];
}
