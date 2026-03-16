<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiviteLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'modele',
        'modele_id',
        'donnees',
        'societe_id',
    ];

    protected $casts = [
        'donnees' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
}
