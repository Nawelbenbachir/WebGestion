<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parametre;
use App\Models\Societe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParametreController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $societeId = $user->last_active_societe_id;
        $societe = Societe::findOrFail($societeId);

        // Vérifie le rôle de l'user dans la société active
        $pivot = $user->societes()->where('societe_id', $societeId)->first()?->pivot;
        $role = $pivot?->role_societe;
        $isProprietaire = $societe->proprietaire_id === $user->id;
        $isAdmin = $role === 'admin';

        // Un simple user n'a pas accès aux paramètres
        if (!$isProprietaire && !$isAdmin) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        // Les sociétés visibles dépendent du rôle
        $societes = $isProprietaire
            ? Societe::where('proprietaire_id', $user->id)->get()
            : collect([$societe]); // L'admin ne voit que la société active

        // Les users de la société active
        $users = User::whereHas('societes', function ($query) use ($societeId) {
            $query->where('societe_id', $societeId);
        })->get();

        return view('parametres.index', compact('societes', 'users', 'isProprietaire', 'isAdmin', 'societe'));
    }
}
