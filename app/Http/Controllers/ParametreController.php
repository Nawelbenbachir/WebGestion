<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parametre;
use App\Models\Societe;
use App\Models\User;

class ParametreController extends Controller
{
    public function index()
    {
        $societes=Societe::all();
        $users=User::all();
        // Afficher les informations de la sociétés et des users 
        return view('parametres.index', compact('societes','users'));
        
        // return view('user.index', compact('users'));
    }
}
