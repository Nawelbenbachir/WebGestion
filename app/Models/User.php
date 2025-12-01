<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Societe;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_active_societe_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function ownedSocietes()
    {
        // Récupère toutes les sociétés dont cet utilisateur est le propriétaire
        return $this->hasMany(Societe::class, 'proprietaire_id');
    }
    public function societes()
    {
        // Récupère toutes les sociétés auxquelles l'utilisateur a accès via la table de pivot
        return $this->belongsToMany(Societe::class, 'societe_user')->withPivot('role_societe');
    }
    public function lastActiveSociete()
        {
            return $this->belongsTo(Societe::class, 'last_active_societe_id');
        }
    public function hasParametresAccess(): bool
        {
            $societeId = $this->last_active_societe_id;

            if (!$societeId) {
                return false; // Pas de société active, pas d'accès
            }

            //  Vérifier si l'utilisateur est le propriétaire de la société active
            $isProprietaire = Societe::where('id', $societeId)
                                    ->where('proprietaire_id', $this->id)
                                    ->exists();

            if ($isProprietaire) {
                return true;
            }

            //  Vérifier si l'utilisateur est admin dans la table pivot (societe_user)
            $isAdminInSociete = $this->societes()
                                    ->where('societe_id', $societeId)
                                    ->wherePivot('role_societe', 'admin') // Assurez-vous que 'role_societe' est le nom de la colonne de rôle dans la pivot
                                    ->exists();

            return $isAdminInSociete;
        }
}

