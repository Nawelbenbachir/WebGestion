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
    
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        return view('produits.show', compact('produit'));
    }

    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        return view('produits.edit', compact('produit'));
    }
    /**
     * Mise à jour du produit existant.
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'code_societe'   => 'required|string|max:255',
            'code_produit'   => 'required|string|max:255|unique:produits,code_produit,' . $produit->id,
            'code_comptable' => 'required|string|max:255',
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

};