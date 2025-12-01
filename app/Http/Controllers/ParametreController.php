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
        $userId = $user->id;

        $societes = Societe::where('proprietaire_id', $userId)->get();
        // L'admin veut voir les utilisateurs de la société active.
        $lastActiveSocieteId = $user->last_active_societe_id;
        if ($lastActiveSocieteId) {
            // Utiliser la relation Many-to-Many pour récupérer les utilisateurs de la société active
            $users = User::whereHas('societes', function ($query) use ($lastActiveSocieteId) {
                $query->where('societe_id', $lastActiveSocieteId);
            })->get();
        } else {
             // Si aucune société active n'est définie
            $users = collect(); 
        }
        // Afficher les informations de la sociétés et des users 
        return view('parametres.index', compact('societes','users'));
        
        // return view('user.index', compact('users'));
    }
}
