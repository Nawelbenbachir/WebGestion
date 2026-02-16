<?php

namespace App\Http\Controllers;

use App\Models\Reglement;
use App\Models\EnTeteDocument;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function create()
    {
        $societeId=session('current_societe_id');
        $documents = EnTeteDocument::where('statut','!=','paye')
        ->where('societe_id', $societeId)
        ->where(function ($query) {
         $query->where('type_document', 'F')
              ->orWhere('type_document', 'A');
            })
        ->get();
        $clients=Client::where('id_societe', $societeId)
        ->get();
        $prochainNumero=$this->genererNumeroReglement($societeId);
        return view('reglements.create', compact('documents','clients','prochainNumero'));
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
    private function genererNumeroReglement($societeId)
    {
        //  Récupérer le format depuis la table parametres
        // On cherche directement dans la table parametres liée à la société
        $formatChoisi = DB::table('societes')
            ->where('id', $societeId)
            ->value('format_numero_document') ?? 'simple';

        $prefix ='REG';

        $annee = now()->format('Y');
        $mois = now()->format('m');

        //  Construction de la base de recherche
        $baseCode = ($formatChoisi === 'mensuel') 
            ? "{$prefix}-{$annee}-{$mois}-" 
            : "{$prefix}-{$annee}-";

        $nbTiretsAttendus = ($formatChoisi === 'mensuel') ? 3 : 2;

        $dernierReg = Reglement::where('societe_id', $societeId)
            ->where('numero_reglement', 'LIKE', $baseCode . '%')
            // On s'assure que le format correspond (filtrage par nombre de tirets)
            ->whereRaw("(LENGTH(numero_reglement) - LENGTH(REPLACE(numero_reglement, '-', ''))) = ?", [$nbTiretsAttendus])
            ->orderByRaw('LENGTH(numero_reglement) DESC')
            ->orderBy('numero_reglement', 'desc')
            ->first();

            $compteur = 1;

        if ($dernierReg) {
            // Extraction du dernier segment (le numéro)
            $parties = explode('-', $dernierReg->numero_reglement);
            $dernierNombre = end($parties);
            if (is_numeric($dernierNombre)) {
                $compteur = (int)$dernierNombre + 1;
            }
        }

        // Formatage final (ex: F-2024-001 ou F-2024-05-001)
        return $baseCode . str_pad($compteur, 3, '0', STR_PAD_LEFT);
    }

}