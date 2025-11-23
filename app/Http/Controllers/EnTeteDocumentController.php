<?php

namespace App\Http\Controllers;

use App\Models\EnTeteDocument;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Parametre;
use App\Models\LigneDocument;
use Illuminate\Support\Facades\Validator;

class EnTeteDocumentController extends Controller
{
    /**
     * Affiche la liste des documents (filtrés par type)
     */
    public function index(Request $request)
    {
        $typeMap = [
        'facture' => 'F',
        'devis'   => 'D',
        'avoir'   => 'A',
        ];

        $type = $request->get('type', 'devis');
        $typeCode = $typeMap[$type] ?? 'D'; // D par défaut

        $documents = EnTeteDocument::with(['societe', 'client'])
            ->where('type_document', $typeCode)
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

    // Récupération de l’ID de la société depuis la table "parametres"
    $parametre = Parametre::first();
    $idSociete = $parametre ? $parametre->derniere_societe : null;

    if (!$idSociete) {
        return back()->withErrors('Aucune société sélectionnée dans les paramètres.');
    }

    // Récupération des clients et produits liés à cette société
    $clients = Client::where('id_societe', $idSociete)->get();
    $produits = Produit::where('id_societe', $idSociete)->get();

    // Choix de la vue selon le type de document
    $view = match ($type) {
        'facture' => 'factures.create',
        'avoir'   => 'avoirs.create',
        default   => 'devis.create',
    };

     return view($view, compact('type', 'clients', 'produits', 'parametre'));
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
        'client_code'   => 'required|string|exists:clients,code_cli',
        'lignes'        => 'required|array|min:1',
        'lignes.*.produit_code'      => 'required|string',
        'lignes.*.quantite'          => 'required|numeric|min:1',
        'lignes.*.prix_unitaire_ht'  => 'required|numeric|min:0',
        'lignes.*.taux_tva'          => 'required|numeric|min:0',
        'lignes.*.total_ttc'         => 'required|numeric|min:0',
    ]);
    
    $typeMap = [
    'facture' => 'F',
    'devis'   => 'D',
    'avoir'   => 'A',
    ];

    $typeDocument = $typeMap[$validated['type_document']] ?? null;

    //  Récupérer le client à partir de son code
    $client = Client::where('code_cli', $validated['client_code'])->firstOrFail();

    //  Créer le document (en-tête)
    $document = EnTeteDocument::create([
        'societe_id'    => $validated['societe_id'],
        'code_document' => $validated['code_document'],
        'type_document' => $typeDocument,
        'date_document' => $validated['date_document'],
        'total_ht'      => $validated['total_ht'],
        'total_tva'     => $validated['total_tva'],
        'total_ttc'     => $validated['total_ttc'],
        'client_id'     => $client->id,
        'client_nom'    => $client->societe,
        'adresse'       => $client->adresse ?? null,
        'telephone'     => $client->telephone ?? null,
        'email'         => $client->email ?? null,
        'statut'        => $validated['statut'] ?? 'brouillon',
    ]);

    //  Créer les lignes
    foreach ($validated['lignes'] as $ligne) {
        LigneDocument::create([
            'document_id'   => $document->id, // Associer à l'en-tête
            'produit_code'  => $ligne['produit_code'],
            'description'   => $ligne['description'] ?? '',
            'quantite'      => $ligne['quantite'],
            'prix_unitaire_ht'       => $ligne['prix_unitaire_ht'],
            'taux_tva'      => $ligne['taux_tva'],
            'total_ttc'     => $ligne['total_ttc'],
        ]);
    }
   

   
    // //  Redirection
    // return redirect()
    //     ->route('documents.index', ['type' => $validated['type_document']])
       // ->with('success', ucfirst($validated['type_document']) . ' créé avec succès.');
}

    /**
     * Formulaire d’édition
     */
    public function edit($id)
    {
        $document = EnTeteDocument::findOrFail($id);

        // Récupère l'ID de la société du document
        $idSociete = $document->societe_id;

        // Récupère tous les clients liés à cette société
        $clients = Client::where('id_societe', $idSociete)->get();

        // Récupère les produits de la société
        $produits = Produit::where('id_societe', $idSociete)->get();
        $document = EnTeteDocument::findOrFail($id);

            $view = match ($document->type_document) {
                'facture' => 'factures.edit',
                'avoir'   => 'avoirs.edit',
                default   => 'devis.edit',
            };
             // Si requête AJAX (fetch), renvoyer uniquement le formulaire
            if (request()->ajax()) {
                return view($view, compact('document', 'clients', 'produits'));
            }

            // Sinon renvoyer la page complète
            return view($view, compact('document', 'clients', 'produits'));

        
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
