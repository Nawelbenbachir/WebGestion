<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Societe;
use Illuminate\Support\Collection;

class SocieteComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        //Initialisation obligatoire de ces variables avant tout bloc conditionnel.
        // Ceci garantit qu'elles sont définies même si Auth::check() est faux.
        $currentSocieteId = null;
        $availableSocietes = Collection::make();
        $currentSociete = null;


        // On n'exécute la logique spécifique que si un utilisateur est connecté
        if (Auth::check()) {
            $user = Auth::user();
            
            // Récupérer toutes les sociétés auxquelles l'utilisateur est lié
            
            $availableSocietes = $user->ownedSocietes->merge($user->societes)->unique('id');
            
            //  Tenter de déterminer l'ID de la société active (depuis la session)
            $targetSocieteId = session('current_societe_id');
            
            // Si l'ID en session est valide pour cet utilisateur
            if ($targetSocieteId && $availableSocietes->contains('id', $targetSocieteId)) {
                $currentSocieteId = (int) $targetSocieteId;
                $currentSociete = $availableSocietes->firstWhere('id', $currentSocieteId);

            } elseif ($availableSocietes->isNotEmpty()) {
                // Si la session est vide ou invalide, on prend la première société disponible comme défaut
                $currentSociete = $availableSocietes->first();
                $currentSocieteId = $currentSociete->id;
                
                // Mettre à jour la session avec le nouvel ID sélectionné
                session(['current_societe_id' => $currentSocieteId]);
            }
        }

        //  Injecter les variables dans la vue
        $view->with('availableSocietes', $availableSocietes);
        $view->with('currentSocieteId', $currentSocieteId);
        $view->with('currentSociete', $currentSociete);
    }
}