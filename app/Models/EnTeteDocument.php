<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnTeteDocument extends Model
{
    protected $fillable = [
        'type_document',
        'date_document',
        'client_id',
        'total_ht',
        'total_ttc',
        'tva',
        'statut',
        'remise',
        'commentaire',
    ];
}
