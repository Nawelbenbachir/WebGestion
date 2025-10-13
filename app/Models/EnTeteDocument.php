<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnTeteDocument extends Model
{
    protected $fillable = [
        'societe_id',
        'code_document',
        'type_document', 
        'date_document',
        'total_ht',
        'total_tva',
        'total_ttc',
        'client_id',
        'client_nom',
        'logo',
        'adresse',
        'telephone',
        'email',

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
}
