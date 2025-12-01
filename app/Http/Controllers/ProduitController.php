<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class ProduitController extends Controller
{
    /**
     * Affiche la liste des produits (filtrés par société active).
     */
    public function index()
    {
        //  Récupération de la société active à partir de la session
        $societeId = session('current_societe_id');

        if (!$societeId) {
            return redirect()->route('dashboard')->with('error', 'Veuillez sélectionner une société de travail pour afficher les produits.');
        }

        //  Filtrer les produits de cette société seulement
        $produits = Produit::where('id_societe', $societeId)
            ->orderBy('id', 'desc')
            ->get();

        return view('produits.index', compact('produits'));
    }
    
    /**
     * Affiche le formulaire de création d’un produit
     */
    public function create()
    {
        //  Récupération de la société active à partir de la session
        $societeId = session('current_societe_id');

        if (!$societeId) {
            return redirect()->route('dashboard')->with('error', 'Veuillez sélectionner une société de travail.');
        }

        //  Récupérer toutes les catégories existantes pour la société active
        $categories = Produit::where('id_societe', $societeId)
            ->pluck('categorie') // récupérer uniquement le champ catégorie
            ->unique()           // enlever les doublons
            ->filter()           // enlever les valeurs nulles
            ->values();          // réindexer

        return view('produits.create', compact('categories'));
    }

    /**
     * Affiche le détail d’un produit
     */
    public function show($id)
    {
        $societeId = session('current_societe_id');

        //  SÉCURITÉ : Récupérer le produit seulement s'il appartient à la société active
        $produit = Produit::where('id_societe', $societeId)->findOrFail($id);

        return view('produits.show', compact('produit'));
    }

    /**
     * Affiche le formulaire d’édition d’un produit
     */
    public function edit($id)
    {
        $societeId = session('current_societe_id');

        //  SÉCURITÉ : Récupérer le produit seulement s'il appartient à la société active
        $produit = Produit::where('id_societe', $societeId)->findOrFail($id);
        
        //  Récupérer toutes les catégories existantes pour la société active
        $categories = Produit::where('id_societe', $societeId)
            ->pluck('categorie')
            ->unique()
            ->filter()
            ->values();

        return view('produits.edit', compact('produit', 'categories'));
    }

    /**
     * Enregistre un nouveau produit dans la base
     */
    public function store(Request $request)
    {
        //  Récupération de la société active à partir de la session
        $societeId = session('current_societe_id');

        if (!$societeId) {
            return back()->withErrors('Erreur : Aucune société de travail sélectionnée.');
        }

        //  SÉCURITÉ : Scoper les règles 'unique' à la société active
        $validated = $request->validate([
            // code_comptable unique DANS CETTE SOCIÉTÉ
            'code_comptable' => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('produits', 'code_comptable')->where(fn ($query) => $query->where('id_societe', $societeId)),
            ],
            // code_produit unique DANS CETTE SOCIÉTÉ
            'code_produit' => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('produits', 'code_produit')->where(fn ($query) => $query->where('id_societe', $societeId)),
            ],
            'description'          => 'required|string|max:255',
            'prix_ht'              => 'required|numeric|min:0',
            'tva'                  => 'nullable|numeric|min:0',
            'qt_stock'             => 'nullable|integer|min:0',
            'categorie'            => 'nullable|string|max:255',
            'nouvelle_categorie'   => 'nullable|string|max:255',
        ]);

        // Si l'utilisateur a saisi une nouvelle catégorie, on l'utilise
        if (!empty($validated['nouvelle_categorie'])) {
            $validated['categorie'] = $validated['nouvelle_categorie'];
        }

        // Supprimer le champ temporaire pour ne pas créer de colonne inutile
        unset($validated['nouvelle_categorie']);
        
        // Ajouter l'id de la société active
        $validated['id_societe'] = $societeId;

        // Génération automatique si vide
        if (empty($validated['code_produit'])) {
            $validated['code_produit'] = 'PRD' . strtoupper(substr($validated['description'], 0, 3)) . rand(100, 999);
        }

        if (empty($validated['code_comptable'])) {
            $validated['code_comptable'] = 'CPT' . strtoupper(substr($validated['description'], 0, 1)) . now()->format('YmdHis');
        }

        Produit::create($validated);
        
        return redirect() 
            ->route('produits.index') 
            ->with('success', ' Produit créé avec succès.');
    }

    /**
     * Mise à jour du produit existant.
     */
    public function update(Request $request, Produit $produit)
    {
        $societeId = session('current_societe_id');

        //  SÉCURITÉ : Vérification IDOR (Insecure Direct Object Reference)
        if ($produit->id_societe !== $societeId) {
            abort(403, 'Accès non autorisé à ce produit.');
        }

        //  SÉCURITÉ : Scoper les règles 'unique' à la société active et ignorer l'ID du produit
        $validated = $request->validate([
            // code_comptable unique DANS CETTE SOCIÉTÉ, ignorer l'ID actuel
            'code_comptable' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('produits', 'code_comptable')
                    ->where(fn ($query) => $query->where('id_societe', $societeId))
                    ->ignore($produit->id),
            ],
            // code_produit unique DANS CETTE SOCIÉTÉ, ignorer l'ID actuel
            'code_produit' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('produits', 'code_produit')
                    ->where(fn ($query) => $query->where('id_societe', $societeId))
                    ->ignore($produit->id),
            ],
            'description'   => 'nullable|string|max:255',
            'prix_ht'   => 'nullable|numeric|min:0',
            'tva'  => 'nullable|numeric|min:0',
            'qt_stock' => 'nullable|integer|min:0',
            'categorie'   => 'nullable|string|max:255',
        ]);

        $produit->update($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', ' Produit mis à jour avec succès.');
    }

    /**
     * Suppression d’un produit
     */
    public function destroy($id)
    {
        $societeId = session('current_societe_id');
        
        // SÉCURITÉ : Récupérer et détruire le produit en filtrant par la société active
        $produit = Produit::where('id_societe', $societeId)->findOrFail($id);
        
        $produit->delete();

        return redirect()->route('produits.index')->with('success', ' Produit supprimé avec succès.');
    }
}