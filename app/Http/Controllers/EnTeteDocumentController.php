<?php

namespace App\Http\Controllers;

use App\Models\EnTeteDocument;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Parametre;

class EnTeteDocumentController extends Controller
{
    /**
     * Affiche la liste des documents (filtrés par type)
     */
    public function index(Request $request)
    {
        // Si un type est passé dans l’URL (ex: devis, facture, avoir)
        $type = $request->get('type', 'devis'); // valeur par défaut : devis

        // On récupère uniquement les documents du type demandé
        $documents = EnTeteDocument::with(['societe', 'client'])
            ->where('type_document', $type)
            ->get();

        // Vue dynamique selon le type
        $view = match ($type) {
            'facture' => 'facture.index',
            'avoir'   => 'avoir.index',
            default   => 'devis.index',
        };

        return view($view, compact('documents'));
    }

    /**
     * Affiche un document précis selon son type
     */
    public function show($id)
    {
        $document = EnTeteDocument::with(['societe', 'client'])->findOrFail($id);

        $view = match ($document->type_document) {
            'facture' => 'factures.show',
            'avoir'   => 'avoirs.show',
            default   => 'devis.show',
        };

        return view($view, compact('document'));
    }

    /**
     * Formulaire de création (vue selon le type demandé)
     */


public function create(Request $request)
{
    $type = $request->get('type', 'devis');

    //  Récupération de l’ID de la société depuis la table "parametres"
    $parametre = Parametre::first();
    $idSociete = $parametre ? $parametre->derniere_societe : null;

    if (!$idSociete) {
        return back()->withErrors('Aucune société sélectionnée dans les paramètres.');
    }

    // Récupération des clients et produits liés à cette société
    $clients = Client::where('id_societe', $idSociete)->get();
    $produits = Produit::where('id_societe', $idSociete)->get();

  
    $view = match ($type) {
        'facture' => 'factures.create',
        'avoir'   => 'avoirs.create',
        default   => 'devis.create',
    };

 
    return view($view, compact('type', 'clients', 'produits'));
}




    public function store(Request $request)
{
    // Validation en-tête + lignes
    $validated = $request->validate([
        'societe_id'    => 'required|exists:societes,id',
        'code_document' => 'required|string|max:50|unique:en_tete_documents,code_document',
        'type_document' => 'required|in:facture,devis,avoir',
        'date_document' => 'required|date',
        'total_ht'      => 'required|numeric|min:0',
        'total_tva'     => 'required|numeric|min:0',
        'total_ttc'     => 'required|numeric|min:0',
        'client_id'     => 'required|exists:clients,id',
        'client_nom'    => 'nullable|string|max:255',
        'logo'          => 'nullable|string',
        'adresse'       => 'nullable|string',
        'telephone'     => 'nullable|string|max:20',
        'email'         => 'nullable|email',
        'lignes'        => 'required|array|min:1',
        'lignes.*.produit_code'      => 'required|string',
        'lignes.*.quantite'          => 'required|numeric|min:1',
        'lignes.*.prix_unitaire_ht'  => 'required|numeric|min:0',
        'lignes.*.taux_tva'          => 'required|numeric|min:0',
        'lignes.*.total_ttc'         => 'required|numeric|min:0',
    ]);

    // Créer l'en-tête
    $document = EnTeteDocument::create($validated);

    // Créer les lignes
    foreach ($validated['lignes'] as $ligne) {
        LigneDocument::create([
            'document_id' => $document->id,  // Associer la ligne à l'en-tête
            'produit_code' => $ligne['produit_code'],
            'quantite' => $ligne['quantite'],
            'prix_ht' => $ligne['prix_ht'],
            'taux_tva' => $ligne['taux_tva'],
            'total_ttc' => $ligne['total_ttc'],
        ]);
    }

    return redirect()
        ->route('documents.index', ['type' => $validated['type_document']])
        ->with('success', ucfirst($validated['type_document']) . ' créé avec succès.');
}


    /**
     * Formulaire d’édition
     */
    public function edit($id)
    {
        $document = EnTeteDocument::findOrFail($id);

        $view = match ($document->type_document) {
            'facture' => 'factures.edit',
            'avoir'   => 'avoirs.edit',
            default   => 'devis.edit',
        };

        return view($view, compact('document'));
    }

    /**
     * Mise à jour d’un document
     */
    public function update(Request $request, $id)
    {
        $document = EnTeteDocument::findOrFail($id);

        $validated = $request->validate([
            'date_document' => 'date',
            'total_ht'      => 'numeric|min:0',
            'total_tva'     => 'numeric|min:0',
            'total_ttc'     => 'numeric|min:0',
            'client_nom'    => 'nullable|string|max:255',
            'logo'          => 'nullable|string',
            'adresse'       => 'nullable|string',
            'telephone'     => 'nullable|string|max:20',
            'email'         => 'nullable|email',
        ]);

        $document->update($validated);

        return redirect()
            ->route('documents.index', ['type' => $document->type_document])
            ->with('success', ucfirst($document->type_document) . ' mis à jour avec succès.');
    }

    /**
     * Suppression d’un document
     */
     public function destroy($id)
    {
        $document = EnTeteDocument::findOrFail($id);
        $type = $document->type_document;
        $document->delete();

        return redirect()
            ->route('documents.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' supprimé avec succès.');
    }
}
