<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;
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
