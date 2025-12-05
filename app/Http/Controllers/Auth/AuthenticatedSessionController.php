<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider; 



class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // La vue de login est maintenant simple, sans sélecteur de société.
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request and set the active society context.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); // 1. Authentifie l'utilisateur (Email/Password)
        $request->session()->regenerate();

        $user = Auth::user();
        
        //  Déterminer les sociétés accessibles (Propriétaire + Collaborateur)
        // La méthode 'societes' dans User doit inclure un merge de ownedSocietes et societes
        $availableSocietes = $user->ownedSocietes->merge($user->societes)->unique('id');
        $societesCount = $availableSocietes->count();


        if ($societesCount === 0) {
            //  L'utilisateur n'a aucune société liée.
            
            // Sécurité: On déconnecte l'utilisateur pour le forcer à passer par la création
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Redirection vers la page de création obligatoire
            return redirect()->route('societes.create')->with('warning', 'Veuillez créer votre première société pour accéder à l\'application.');
        }

        //  L'utilisateur a au moins une société.
        
        $targetSocieteId = null;

        //  Vérifier la préférence persistante (last_active_societe_id)
        if ($user->last_active_societe_id && $availableSocietes->contains('id', $user->last_active_societe_id)) {
            // Si la préférence existe et est valide pour cet utilisateur, on l'utilise
            $targetSocieteId = $user->last_active_societe_id;
        } 
        
        //  Si l'utilisateur n'a qu'une seule société (même sans préférence persistante)
        elseif ($societesCount === 1) {
            $uniqueSociete = $availableSocietes->first();
            $targetSocieteId = $uniqueSociete->id;
        }
        
        // Définir la session et la préférence BDD si un ID cible a été trouvé
        if ($targetSocieteId) {
            session(['current_societe_id' => $targetSocieteId]);
            
            // Mettre à jour la colonne last_active_societe_id si elle est différente de l'actuelle
            if ($user->last_active_societe_id !== $targetSocieteId) {
                 $user->update(['last_active_societe_id' => $targetSocieteId]);
            }
        }
        
        //  Redirection finale
        // Si $targetSocieteId n'est pas trouvé (Cas: plusieurs sociétés sans préférence), 
        // l'utilisateur est redirigé vers le dashboard, mais sans session active. 
        // Le View Composer/Middleware de la navbar forcera la sélection manuelle.

        return redirect()->intended('/dashboard'); // OU '/home', selon votre application
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Oublier la société active de la session
        $request->session()->forget('current_societe_id'); 
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}