<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\Parametre;

class ProduitController extends Controller
{
    public function index()
    {
        // Récupérer l'ID de la dernière société
        $parametre = Parametre::first();
        $societeId = $parametre ? $parametre->derniere_societe : null;

        // Filtrer les produits de cette société seulement
        $produits = Produit::when($societeId, function($query, $societeId) {
            return $query->where('id_societe', $societeId);
        })
        ->orderBy('id', 'desc')
        ->get();

        return view('produits.index', compact('produits'));
    }
    public function create()
    {
     $parametre = Parametre::first();
        $societeId = $parametre ? $parametre->derniere_societe : null;

        // Récupérer toutes les catégories existantes
        $categories = Produit::when($societeId, function($query, $societeId) {
            return $query->where('id_societe', $societeId);
        })
        ->pluck('categorie') // récupérer uniquement le champ catégorie
        ->unique()          // enlever les doublons
        ->filter()          // enlever les valeurs nulles
        ->values();         // réindexer

        return view('produits.create', compact('categories'));
    }

    
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        return view('produits.show', compact('produit'));
    }

    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
       $categories = Produit::distinct()->pluck('categorie'); 
        return view('produits.edit', compact('produit', 'categories'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'code_comptable' => 'nullable|string|max:255|unique:produits,code_comptable',
        'code_produit'   => 'nullable|string|max:255|unique:produits,code_produit',
        'description'    => 'required|string|max:255',
        'prix_ht'        => 'required|numeric|min:0',
        'tva'            => 'nullable|numeric|min:0',
        'qt_stock'       => 'nullable|integer|min:0',
        'categorie'      => 'nullable|string|max:255',
        'nouvelle_categorie' => 'nullable|string|max:255',
]);

// Si l'utilisateur a saisi une nouvelle catégorie, on l'utilise
if (!empty($validated['nouvelle_categorie'])) {
    $validated['categorie'] = $validated['nouvelle_categorie'];
}

// Supprimer le champ temporaire pour ne pas créer de colonne inutile
unset($validated['nouvelle_categorie']);
   

    // Récupérer l'id de la société depuis les paramètres
    $parametre = Parametre::first();
    $idSociete = $parametre ? $parametre->derniere_societe : null;

    if (!$idSociete) {
        return back()->withErrors('Aucune société sélectionnée dans les paramètres.');
    }

    $validated['id_societe'] = $idSociete;

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
        ->with('success', '✅ Produit créé avec succès.');
}

    /**
     * Mise à jour du produit existant.
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'code_comptable' => 'required|string|max:255|unique:produits,code_comptable,' . $produit->id,
            'code_produit'   => 'required|string|max:255|unique:produits,code_produit,' . $produit->id,
            'description'    => 'nullable|string|max:255',
            'prix_ht'        => 'nullable|numeric|min:0',
            'tva'            => 'nullable|numeric|min:0',
            'qt_stock'       => 'nullable|integer|min:0',
            'categorie'      => 'nullable|string|max:255',
        ]);

        $produit->update($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', '✅ Produit mis à jour avec succès.');
    }
     public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return redirect()->route('produits.index')->with('success', '🗑️ Produit supprimé avec succès.');
    }

};