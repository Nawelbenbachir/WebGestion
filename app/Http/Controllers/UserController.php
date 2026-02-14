<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs.
     */
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
        
    }


    /**
     * Affiche le formulaire de création d’un utilisateur dans le modal.
     */
    public function create()
    {
        
        return view('user.create'); 
    }

    /**
     * Affiche le formulaire d’édition d’un utilisateur dans le modal.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        return view('user.edit', compact('user'));
    }


    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request)
    {
       
        //  Validation des données 
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8|confirmed', 
            'role'      => 'nullable|string|in:admin,manager,user', 
            
        ]);
         $currentSocieteId = session('current_societe_id');
         if (!$currentSocieteId) {
        return response()->json([
            'success' => false, 
            'message' => 'Erreur : Aucune société active sélectionnée.'
        ], 400);
    }

        //  Création de l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? 'user',
            'password' => Hash::make($validated['password']), 
            'societe_id' => $currentSocieteId, 
            'last_active_societe_id' => $currentSocieteId,

        ]);

        //  Réponse (pour le modal AJAX)
        if ($request->ajax() || $request->wantsJson()) {
           return response()->json([
        'success' => true,
        'message' => 'Utilisateur créé et rattaché à la société actuelle.'
    ]);
        }

        return redirect()->route('user.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    /**
     * Met à jour un utilisateur existant.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            // Ignore l'email de l'utilisateur actuel lors de la vérification de l'unicité
            'email'     => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], 
            'password'  => 'nullable|string|min:8|confirmed', // Le mot de passe est facultatif à la mise à jour
            'role'      => 'nullable|string|in:admin,user',
        ]);

        //  Préparation des données pour la mise à jour
        $dataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? 'user',
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        // 3. Mise à jour
        $user->update($dataToUpdate);

        // 4. Réponse (pour le modal AJAX)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Utilisateur mis à jour avec succès.']);
        }

        return redirect()->route('user.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur.
     */
public function destroy($id)
{
    $societeId = session('current_societe_id');
    $user = User::findOrFail($id);
    
    //  Récupérer la société pour vérifier qui est le propriétaire
    $societe = Societe::findOrFail($societeId);

    //  On ne peut pas retirer le propriétaire
    if ($user->id === $societe->proprietaire_id) {
        return response()->json([
            'success' => false, 
            'message' => 'Impossible de retirer le propriétaire de la société.'
        ], 403);
    }
   
    //  On détache l'utilisateur de la société (table pivot)
    // detach() supprime uniquement la ligne dans societe_user
    $user->societes()->detach($societeId);

    if ($user->id === auth()->id()) {
        
        // On nettoie sa session
        session()->forget('current_societe_id');
        $user->update(['last_active_societe_id' => null]);
         return response()->json([
            'success' => true, 
            'redirect' => route('tableau-de-bord'), 
            'message' => 'Vous avez quitté la société avec succès.'
        ]);
    }

    //  Si c'était sa dernière société active, on remet à null
    if ($user->last_active_societe_id == $societeId) {
        $user->update(['last_active_societe_id' => null]);
    }

    return response()->json([
        'success' => true, 
        'message' => 'L\'utilisateur n\'a plus accès à cette société.'
    ]);
}
    
}