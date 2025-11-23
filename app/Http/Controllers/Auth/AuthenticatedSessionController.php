<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Societe;
use App\Models\Parametre;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $societes = Societe::all();
        $parametre = Parametre::first();
        $societeDefaut = $parametre ? $parametre->derniere_societe : null;

        return view('auth.login', compact('societes', 'societeDefaut'));
    }

    /**
     * Handle an incoming authentication request.
     */
  public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate(); // authentifie l'utilisateur

    $request->session()->regenerate();

    // Récupérer l'ID de la société sélectionnée
    $societeId = $request->input('societe_id');

    if ($societeId) {
        // Ici le trigger se charge de supprimer l'ancienne ligne si elle existe
        //sans trigger : c'est truncate qui supp la ligne existante
        Parametre::truncate(); 
        Parametre::create(['derniere_societe' => $societeId]);
    }


    return redirect()->intended(route('clients.index', absolute: false));
}
    protected function authenticated(Request $request, $user)
{
    $societeId = $request->input('societe_id');
    // if ($societeId) {
    //     // Mettre à jour ou créer la ligne Parametre pour stocker la dernière société
    //     Parametre::updateOrCreate([], ['derniere_societe' => $societeId]);
    // }
   
    return redirect()->intended('dashboard');
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
