<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'societe_user')
                    ->withPivot('role_societe')
                    ->withTimestamps();
    }

    /**
     * Relation avec l'utilisateur propriétaire unique.
     */
    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

}
