<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;

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

    /**
     * Met à jour une société.
     */
    public function update(Request $request, Societe $societe)
    {

        $validated = $request->validate([
            'nom_societe' => 'required|string|max:255',
            'email' => 'required|email|unique:societes,email,' . $societe->id,
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

        $societe->update($validated);

        return redirect()->route('parametres.index')->with('success', 'Société mise à jour avec succès.');
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

