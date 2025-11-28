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
        // Pas de filtre nécessaire, on récupère tous les utilisateurs
        $users = User::orderBy('name')->paginate(10); 

        
        return view('user.index', compact('users'));
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

        //  Création de l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? 'user',
            'password' => Hash::make($validated['password']), // Hacher le mot de passe
        ]);

        //  Réponse (pour le modal AJAX)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => ' Utilisateur ajouté avec succès.']);
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
        $user = User::findOrFail($id);

        //  Vérifier si l'utilisateur à supprimer est l'utilisateur actuellement connecté
        if (Auth::check() && Auth::id() === $user->id) {
            
            // Déconnecter l'utilisateur avant la suppression
            Auth::logout();

            // Suppression 

            $user->delete();

            //  Invalider la session et regénérer le jeton
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            //  Redirection vers la page de connexion
            return redirect('/login')->with('status', 'Votre compte a été supprimé.');
        }

        // Si ce n'est pas l'utilisateur connecté ou si la requête est AJAX (autre utilisateur)
        $user->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => ' Utilisateur supprimé avec succès.']);
        }

        return redirect()->route('users.index')->with('success', ' Utilisateur supprimé avec succès.');
    }

    
}