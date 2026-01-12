<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;
use App\Models\Parametre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'code_societe'       => 'required|string|max:255|unique:societes,code_societe',
            'nom_societe'        => 'required|string|max:255',
            'email'              => 'required|email|unique:societes,email',
            'siret'              => 'nullable|string|max:14',
            'telephone'          => 'nullable|string|max:20',
            'adresse1'           => 'nullable|string|max:255',
            'adresse2'           => 'nullable|string|max:255',
            'complement_adresse' => 'nullable|string|max:255',
            'code_postal'        => 'nullable|string|max:10',
            'ville'              => 'nullable|string|max:255',
            'pays'               => 'nullable|string|max:255',
            'iban'               => 'nullable|string|max:34',
            'swift'              => 'nullable|string|max:11',
            'tva'                => 'nullable|string|max:20',
            'logo'               => 'nullable|string|max:255',
        ]);

        // On utilise la transaction et on récupère la société créée
        $societe = DB::transaction(function () use ($validated, $user) {
            
            // 1. Création de la Société
            $newSociete = Societe::create(array_merge($validated, [
                'proprietaire_id' => $user->id,
            ]));

            // 2. Attachement du rôle admin dans la table pivot
            $newSociete->users()->attach($user->id, ['role_societe' => 'admin']);

            // 3. Mise à jour de l'utilisateur (BDD)
            $user->forceFill([
                'last_active_societe_id' => $newSociete->id,
            ])->save();

            return $newSociete;
        });

        // 4. Mise à jour de la Session (UNIQUEMENT si la transaction a réussi)
        session(['current_societe_id' => $societe->id]);

        // 5. Redirection
        return redirect()->route('dashboard')
            ->with('success', 'Votre société a été créée avec succès et est maintenant active.');
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
    //  Validation de base : Le champ doit exister et l'ID de société doit être valide.
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

    //  Vérification des droits d'accès aux paramètres pour la société sélectionnée
    if (!$user->hasParametresAccess()) {
        return redirect()->route('dashboard')->with('error', 'Vous n\'avez plus les droits d\'accès aux paramètres pour la société sélectionnée.');
    }

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

