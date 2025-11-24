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

    // Récupération de l’ID de la société depuis la table "parametres"
    $parametre = Parametre::first();
    $idSociete = $parametre ? $parametre->derniere_societe : null;

    $documents = EnTeteDocument::with(['societe', 'client'])
        ->where('type_document', $typeCode)
        ->when($idSociete, fn($q) => $q->where('societe_id', $idSociete))
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
    // Type du document (par défaut : devis)
    $type = $request->get('type', 'devis');

    // Société sélectionnée dans les paramètres
    $parametre = Parametre::first();
    $idSociete = $parametre?->derniere_societe;

    if (!$idSociete) {
        return back()->withErrors('Aucune société sélectionnée dans les paramètres.');
    }

    // Clients + produits liés à cette société
    $clients = Client::where('id_societe', $idSociete)->get();
    $produits = Produit::where('id_societe', $idSociete)->get();

    // Sélection de la vue selon le type
    $view = match ($type) {
        'facture' => 'facture.create',
        'avoir'   => 'avoir.create',
        default   => 'devis.create',
    };

    return view($view, [
        'type'      => $type,
        'clients'   => $clients,
        'produits'  => $produits,
        'parametre' => $parametre,
    ]);
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
        'lignes.*.produit_code'      => 'required|string|exists:produits,code_produit',
        'lignes.*.description'       => 'nullable|string',
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
       // Trouver le produit à partir du code
        $produit = Produit::where('code_produit', $ligne['produit_code'])->firstOrFail();

        LigneDocument::create([
            'document_id'      => $document->id,
            'produit_id'       => $produit->id,   
            'description'   => $ligne['description'] ?? '',
            'quantite'      => $ligne['quantite'],
            'prix_unitaire_ht'       => $ligne['prix_unitaire_ht'],
            'taux_tva'      => $ligne['taux_tva'],
            'total_ttc'     => $ligne['total_ttc'],
        ]);
    }
    //  Redirection
    return redirect()
        ->route('documents.index', ['type' => $validated['type_document']])
       ->with('success', ucfirst($validated['type_document']) . ' créé avec succès.');
}

    /**
     * Formulaire d’édition
     */
    public function edit($id)
    {
        $document = EnTeteDocument::with('lignes.produit')->findOrFail($id);

        // Récupère l'ID de la société du document
        $idSociete = $document->societe_id;

        // Récupère tous les clients liés à cette société
        $clients = Client::where('id_societe', $idSociete)->get();

        // Récupère les produits de la société
        $produits = Produit::where('id_societe', $idSociete)->get();
        //$document = EnTeteDocument::findOrFail($id);

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
    $document = EnTeteDocument::with('lignes')->findOrFail($id);

    // Validation de l'en-tête + lignes
    $validated = $request->validate([
        'date_document' => 'required|date',
        'total_ht'      => 'required|numeric|min:0',
        'total_tva'     => 'required|numeric|min:0',
        'total_ttc'     => 'required|numeric|min:0',
        'client_nom'    => 'nullable|string|max:255',
        'logo'          => 'nullable|string',
        'adresse'       => 'nullable|string',
        'telephone'     => 'nullable|string|max:20',
        'email'         => 'nullable|email',

        // Validation des lignes
        'lignes' => 'sometimes|array',
        'lignes.*.id' => 'nullable|exists:ligne_documents,id', // id des lignes existantes
        'lignes.*.produit_code' => 'required|string|exists:produits,code_produit',
        'lignes.*.description'  => 'nullable|string',
        'lignes.*.quantite'     => 'required|numeric|min:1',
        'lignes.*.prix_unitaire_ht' => 'required|numeric|min:0',
        'lignes.*.taux_tva'         => 'required|numeric|min:0',
        'lignes.*.total_ttc'        => 'required|numeric|min:0',
    ]);

    // Mettre à jour l'en-tête
    $document->update([
        'date_document' => $validated['date_document'],
        'total_ht'      => $validated['total_ht'],
        'total_tva'     => $validated['total_tva'],
        'total_ttc'     => $validated['total_ttc'],
        'client_nom'    => $validated['client_nom'] ?? $document->client_nom,
        'logo'          => $validated['logo'] ?? $document->logo,
        'adresse'       => $validated['adresse'] ?? $document->adresse,
        'telephone'     => $validated['telephone'] ?? $document->telephone,
        'email'         => $validated['email'] ?? $document->email,
    ]);

    // Traiter les lignes
    $ligneIdsFormulaire = [];

    if (!empty($validated['lignes'])) {
        foreach ($validated['lignes'] as $ligneData) {
            $produit = Produit::where('code_produit', $ligneData['produit_code'])->first();

            if (isset($ligneData['id'])) {
                // Mise à jour d'une ligne existante
                $ligne = LigneDocument::find($ligneData['id']);
                if ($ligne) {
                    $ligne->update([
                        'produit_id'       => $produit->id,
                        'description'      => $ligneData['description'] ?? '',
                        'quantite'         => $ligneData['quantite'],
                        'prix_unitaire_ht' => $ligneData['prix_unitaire_ht'],
                        'taux_tva'         => $ligneData['taux_tva'],
                        'total_ttc'        => $ligneData['total_ttc'],
                    ]);
                    $ligneIdsFormulaire[] = $ligne->id;
                }
            } else {
                // Nouvelle ligne
                $nouvelleLigne = LigneDocument::create([
                    'document_id'      => $document->id,
                    'produit_id'       => $produit->id,
                    'description'      => $ligneData['description'] ?? '',
                    'quantite'         => $ligneData['quantite'],
                    'prix_unitaire_ht' => $ligneData['prix_unitaire_ht'],
                    'taux_tva'         => $ligneData['taux_tva'],
                    'total_ttc'        => $ligneData['total_ttc'],
                ]);
                $ligneIdsFormulaire[] = $nouvelleLigne->id;
            }
        }
    }

    // Supprimer les lignes retirées du formulaire
    $document->lignes()->whereNotIn('id', $ligneIdsFormulaire)->delete();

    $typeReverseMap = [
    'F' => 'facture',
    'D' => 'devis',
    'A' => 'avoir',
    ];

    $typeTexte = $typeReverseMap[$document->type_document] ?? 'devis';

    return redirect()
        ->route('documents.index', ['type' => $typeTexte])
        ->with('success', ucfirst($typeTexte) . ' mis à jour avec succès.');

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
