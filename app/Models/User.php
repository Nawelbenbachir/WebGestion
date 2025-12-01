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
}
