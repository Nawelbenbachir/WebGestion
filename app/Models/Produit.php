<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Loggable;

class Produit extends Model
{
    use HasFactory;
    use Loggable;
    protected $fillable = [
        'id_societe',
        'code_produit',
        'code_comptable',
        'description',
        'prix_ht',
        'tva',
        'qt_stock',
        'categorie',
    ];
}
