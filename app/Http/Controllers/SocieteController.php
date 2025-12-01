<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;
use App\Models\Parametre;
use Illuminate\Support\Facades\Auth;

class SocieteController extends Controller
{
    /**
     * Affiche la liste des sociétés.
     */
    public function index()
    {
        $societes = Societe::all();
        return view('parametres.index', compact('societes'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('parametres.create');
    }

    /**
     * Enregistre une nouvelle société.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_societe' => 'required|string|max:255|unique:societes,code_societe',
            'nom_societe' => 'required|string|max:255',
            'email' => 'required|email|unique:societes,email',
            'siret' => 'nullable|string|max:14',
            'telephone' => 'nullable|string|max:20',
            'adresse1' => 'nullable|string|max:255',
            'adresse2' => 'nullable|string|max:255',
            'complement_adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
            'swift' => 'nullable|string|max:11',
            'tva' => 'nullable|string|max:20',
            'logo' => 'nullable|string|max:255',
        ]);

        Societe::create($validated);

        return redirect()->route('parametres.index')->with('success', 'Société créée avec succès.');
    }

    /**
     * Affiche les détails d’une société.
     */
    public function show($id)
    {
        $societe = Societe::findOrFail($id);
        return view('parametres.show', compact('societe'));
    }

    /**
     * Affiche le formulaire d’édition.
     */
    public function edit($id)
    {
        $societe = Societe::findOrFail($id);
        return view('parametres.edit', compact('societe'));
    }

 // app/Http/Controllers/SocieteController.php

/**
 * Met à jour une société.
 */
public function update(Request $request, Societe $societe)
{
    // Récupérer l'ID de la société si elle n'est pas déjà bindée
    // Votre code actuel utilise Model Binding, mais si la route est '/societes/{id}', Laravel trouvera la bonne.
    // Assurez-vous que votre route utilise le nom 'societe' ou ajustez la signature de la méthode.
    // Dans votre cas, la route semble être 'parametres' d'après la vue index,
    // mais la méthode 'edit' du controller Societe utilise l'ID en paramètre.
    // L'exemple ci-dessous suppose que l'objet $societe est correctement résolu.
    
    // Si la route est 'societes.update' et utilise l'ID, le binding devrait fonctionner.
    // Exemple d'appel : route('societes.update', $societe->id) avec la méthode PUT/PATCH.
    
    $validated = $request->validate([
        'code_societe' => 'required|string|max:255|unique:societes,code_societe,' . $societe->id, // Ajouter l'exception pour l'unique sur le code
        'nom_societe' => 'required|string|max:255',
        'email' => 'required|email|unique:societes,email,' . $societe->id, // Exclure la société actuelle
        'siret' => 'nullable|string|max:14',
        'telephone' => 'nullable|string|max:20',
        'adresse1' => 'nullable|string|max:255',
        'adresse2' => 'nullable|string|max:255',
        'complement_adresse' => 'nullable|string|max:255',
        'code_postal' => 'nullable|string|max:10',
        'ville' => 'nullable|string|max:255',
        'pays' => 'nullable|string|max:255',
        'iban' => 'nullable|string|max:34',
        'swift' => 'nullable|string|max:11',
        'tva' => 'nullable|string|max:20',
        'logo' => 'nullable|string|max:255',
    ]);

    // La mise à jour est exécutée ici
    $societe->update($validated);

    // Vérifier si c'est une requête AJAX
    if ($request->ajax() || $request->wantsJson()) {
        // Renvoie une réponse JSON pour le script `submitCurrentForm`
        return response()->json(['success' => true, 'message' => 'Société mise à jour avec succès.']);
    }

    // Comportement par défaut si ce n'est pas une requête AJAX
    return redirect()->route('parametres.index')->with('success', 'Société mise à jour avec succès.');
}

    /**
     * Met à jour la société de travail actuelle dans la session.
     */
    public function updateSelection(Request $request)
{
    // 1. Validation de base : Le champ doit exister et l'ID de société doit être valide.
    $request->validate([
        'societe_id' => 'required|exists:societes,id',
    ]);

    $societeId = $request->input('societe_id');
    $user = Auth::user();

    //  Contrôle d'accès : Vérifier si l'utilisateur est propriétaire OU collaborateur de cette société.
    //le sélecteur ne propose que les sociétés accessibles, mais on double la sécurité ici.
    $isOwner = $user->ownedSocietes()->where('id', $societeId)->exists();
    $isCollaborator = $user->societes()->where('societe_id', $societeId)->exists();

    if (!$isOwner && !$isCollaborator) {
        // Si l'utilisateur n'a aucun lien avec cette société, on refuse l'opération.
        return back()->with('error', 'Accès non autorisé à cette société.');
    }

    //  Persistance de la session et de la préférence 
    
    // Définir le contexte de travail dans la session de l'utilisateur
    session(['current_societe_id' => $societeId]);

    // Mettre à jour la préférence dans la table users
    $user->update(['last_active_societe_id' => $societeId]);

    return back()->with('success', 'Société de travail mise à jour.');
}
    /**
     * Supprime une société.
     */
    public function destroy(Societe $societe)
    {
        $societe->delete();
        return redirect()->route('parametres.index')->with('success', 'Société supprimée avec succès.');
    }
}

