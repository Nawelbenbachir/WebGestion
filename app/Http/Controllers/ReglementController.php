<?php

namespace App\Http\Controllers;

use App\Models\Reglement;
use App\Models\EnTeteDocument;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Societe;
use App\Models\ActiviteLog;
use Carbon\Carbon;

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
        ->orderby('numero_reglement','desc')
        ->get();
        return view('reglements.index', compact('reglements'));
    }

    /**
     * Formulaire de création d’un règlement
     */
    public function create(Request $request)
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
        $preselectedClientId = $request->query('client_id');
        $preselectedDocId = $request->query('document_id');
        return view('reglements.create',compact(
        'clients', 
        'documents', 
        'prochainNumero', 
        'preselectedClientId', 
        'preselectedDocId'
        ));
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'client_id'      => 'required|exists:clients,id',
        'document_ids'   => 'required|array|min:1',
        'document_ids.*' => 'exists:en_tete_documents,id',
        'mode_reglement' => 'required|in:carte,virement,cheque,espece',
        'date_reglement' => 'required|date',
        'reference'      => 'nullable|string|max:255',
        'commentaire'    => 'nullable|string',
    ]);

    $societeId = session('current_societe_id');

    // --- ÉTAPE CLÉ : On calcule le compteur de départ avant la boucle ---
    $compteurDepart = $this->getProchainNumeroBase($societeId);

    try {
        DB::transaction(function () use ($request, $societeId, $compteurDepart) {
            $nbCrees = 0;

            foreach ($request->document_ids as $docId) {
                $document = EnTeteDocument::where('id', $docId)
                    ->where('client_id', $request->client_id)
                    ->first();

                if ($document && $document->solde != 0) {
                    // On construit le numéro manuellement : Départ + Nombre de docs déjà traités
                    $numero = $this->formaterNumeroFinal($societeId, $compteurDepart + $nbCrees);

                    Reglement::create([
                        'societe_id'       => $societeId,
                        'client_id'        => $request->client_id,
                        'document_id'      => $docId,
                        'numero_reglement' => $numero,
                        'mode_reglement'   => $request->mode_reglement,
                        'montant'          => $document->solde,
                        'date_reglement'   => $request->date_reglement,
                        'reference'=>$request->reference,
                        'commentaire'=>$request->commentaire,
                    ]);

                    $document->update(['solde' => 0, 'statut' => 'paye']);
                    
                    $nbCrees++; 
                }
            }
        });

        return redirect()->route('reglements.index')->with('success', 'Règlements enregistrés.');

    } catch (\Exception $e) {
        return back()->with('error', 'Erreur : ' . $e->getMessage());
    }
}
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

    public function destroy($id)
    {
        $reglement = Reglement::findOrFail($id);
        if ($reglement->exporte==true) {
            return back()->withErrors(['error' => 'Ce règlement est déjà exporté et ne peut plus être supprimé.']);
        }
        $reglement->delete();

        return redirect()->route('reglements.index')
            ->with('success', 'Règlement supprimé.');
    }

   private function genererNumeroReglement($societeId, $offset = 0) 
    {
        $societe = DB::table('societes')->select('format_numero_document')->where('id', $societeId)->first();
        $formatChoisi = $societe->format_numero_document ?? 'simple';

        $prefix = 'REG';
        $date = now();
        $baseCode = ($formatChoisi === 'mensuel') 
            ? "{$prefix}-{$date->format('Y')}-{$date->format('m')}-" 
            : "{$prefix}-{$date->format('Y')}-";

        // On cherche le dernier numéro pour cette base précise
        $dernierReg = Reglement::where('societe_id', $societeId)
            ->where('numero_reglement', 'LIKE', $baseCode . '%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(numero_reglement, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $compteur = 1;
        if ($dernierReg) {
            $parties = explode('-', $dernierReg->numero_reglement);
            $dernierNombre = end($parties);
            $compteur = is_numeric($dernierNombre) ? (int)$dernierNombre + 1 : 1;
        }

        return $baseCode . str_pad($compteur + $offset, 3, '0', STR_PAD_LEFT);
    }

private function getProchainNumeroBase($societeId)
{
    $date = now();
    $prefix = 'REG-' . $date->format('Y') . '-'; // Exemple simplifié
    
    $dernier = Reglement::where('societe_id', $societeId)
        ->where('numero_reglement', 'LIKE', $prefix . '%')
        ->orderByRaw('CAST(SUBSTRING_INDEX(numero_reglement, "-", -1) AS UNSIGNED) DESC')
        ->first();

    if (!$dernier) return 1;

    $parties = explode('-', $dernier->numero_reglement);
    return (int)end($parties) + 1;
}

// Formate le numéro avec les zéros (ex: 005)
private function formaterNumeroFinal($societeId, $nombre)
{
    $annee = now()->format('Y');
    return "REG-{$annee}-" . str_pad($nombre, 3, '0', STR_PAD_LEFT);
}

public function exportReglements(Request $request)
    {
        $societeId = session('current_societe_id');
        $societe = Societe::findOrFail($societeId);
        $mois = $request->input('mois');
        $annee = $request->input('annee');

        // On récupère les factures (F) et Avoirs (A) validés
       $reglements = Reglement::where('societe_id', $societeId)
            ->whereMonth('date_reglement', $mois)
            ->whereYear('date_reglement', $annee)
            ->with('document.client')
            ->get();

        // Log de l'export
        ActiviteLog::create([
            'user_id'    => auth()->id(),
            'societe_id' => $societeId,
            'action'     => 'export_reglements',
            'modele'     => 'Reglement',
            'modele_id'  => 0, 
            'donnees'    => ['mois' => $mois, 'annee' => $annee, 'nb_documents' => $reglements->count()],
        ]);
        Reglement::whereIn('id', $reglements->pluck('id'))
        ->update(['exporte' => true, 'date_export' => now()]);
        return new StreamedResponse(function() use ($reglements, $societe) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM pour qu'Excel (français) ne casse pas les accents
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, ['Journal', 'Date', 'Compte', 'Nom Compte','Code Client','Libellé', 'Débit', 'Crédit','Numéro de TVA'], ';');

            foreach ($reglements as $reg) {
    $montant = $reg->montant;
    $isNegatif = $montant < 0;
    $montantAbs = abs($montant);
    $date = Carbon::parse($reg->date_reglement)->format('d/m/Y');
    $ref = $reg->numero_reglement;
    $nomClient = $reg->document->client->societe;
    $numTVA = $reg->document->client->tva;
    $codecli = $reg->document->client->code_cli;
    $compteClient = $reg->document->client->code_comptable;

    // LIGNE 1 : BANQUE (512) - l'argent rentre
    fputcsv($handle, [
        $societe->journal_reglements,
        $date,
        $societe->compte_banque,
        'Banque',
        $codecli,
        $ref,
        $isNegatif ? 0 : $montantAbs,  // débit
        $isNegatif ? $montantAbs : 0,  // crédit
        ''
    ], ';');

    // LIGNE 2 : CLIENT (411) - la dette diminue
    fputcsv($handle, [
        $societe->journal_reglements,
        $date,
        $compteClient,
        $nomClient,
        $codecli,
        $ref,
        $isNegatif ? $montantAbs : 0,  // débit (inverse)
        $isNegatif ? 0 : $montantAbs,  // crédit
        $numTVA
    ], ';');
    }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export_reg_' . date('Y-m-d') . '.csv"',
        ]);
    }
}