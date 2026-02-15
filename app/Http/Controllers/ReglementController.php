<?php

namespace App\Http\Controllers;

use App\Models\Reglement;
use App\Models\EnTeteDocument;
use Illuminate\Http\Request;

class ReglementController extends Controller
{
    /**
     * Liste de tous les règlements
     */
    public function index()
    {
        $societeId=session('current_societe_id');
        $reglements = Reglement::with('document')
        ->where('societe_id', $societeId)
        ->get();
        return view('reglements.index', compact('reglements'));
    }

    /**
     * Formulaire de création d’un règlement
     */
    public function create($document_id = null)
    {
        $document = null;
        if ($document_id) {
            $document = EnTeteDocument::findOrFail($document_id);
        }
        return view('reglements.create', compact('document'));
    }

    /**
     * Enregistrement d’un règlement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:en_tete_documents,id',
            'mode'        => 'required|string', // CB, chèque, virement…
            'montant'     => 'required|numeric|min:0.01',
            'date_paiement' => 'required|date',
        ]);

        Reglement::create($validated);

        return redirect()->route('reglements.index')
            ->with('success', 'Règlement enregistré avec succès.');
    }

    /**
     * Affichage d’un règlement
     */
    public function show($id)
    {
        $reglement = Reglement::with('document')->findOrFail($id);
        return view('reglements.show', compact('reglement'));
    }

    /**
     * Formulaire d’édition d’un règlement
     */
    public function edit($id)
    {
        $reglement = Reglement::findOrFail($id);
        return view('reglements.edit', compact('reglement'));
    }

    /**
     * Mise à jour d’un règlement
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'mode'        => 'required|string',
            'montant'     => 'required|numeric|min:0.01',
            'date_paiement' => 'required|date',
        ]);

        $reglement = Reglement::findOrFail($id);
        $reglement->update($validated);

        return redirect()->route('reglements.index')
            ->with('success', 'Règlement mis à jour avec succès.');
    }

    /**
     * Suppression d’un règlement
     */
    public function destroy($id)
    {
        $reglement = Reglement::findOrFail($id);
        $reglement->delete();

        return redirect()->route('reglements.index')
            ->with('success', 'Règlement supprimé.');
    }
}