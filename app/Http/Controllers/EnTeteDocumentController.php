<?php

namespace App\Http\Controllers;

use App\Models\EnTeteDocument;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Produit;
use App\Models\LigneDocument;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EnTeteDocumentController extends Controller
{
    /**
     * Affiche la liste des documents (filtrés par type et par société active).
     */
    public function index(Request $request)
    {
        //  Récupération de la société active à partir de la session
        $societeId = session('current_societe_id');

        // Sécurité : Vérifie si le contexte de travail est défini
        if (!$societeId) {
            return redirect()->route('dashboard')->with('error', 'Veuillez sélectionner une société de travail pour afficher les documents.');
        }

        $typeMap = [
            'facture' => 'F',
            'devis'   => 'D',
            'avoir'   => 'A',
        ];

        $type = $request->get('type', 'devis');
        $typeCode = $typeMap[$type] ?? 'D';

        // Filtrage essentiel : Seulement les documents de la société active
        $documents = EnTeteDocument::with(['societe', 'client'])
            ->where('type_document', $typeCode)
            ->where('societe_id', $societeId)
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
        $societeId = session('current_societe_id');

        //  Sécurité : Récupère le document seulement s'il appartient à la société active
        $document = EnTeteDocument::with(['societe', 'client'])
            ->where('societe_id', $societeId)
            ->findOrFail($id);

        $view = match ($document->type_document) {
            'F' => 'factures.show',
            'A' => 'avoirs.show',
            default => 'devis.show',
        };

        return view($view, compact('document'));
    }

    /**
     * Formulaire de création (vue selon le type demandé)
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'devis');

        // Récupération de l'ID de la société active
        $societeId = session('current_societe_id');

        if (!$societeId) {
            return back()->withErrors('Aucune société sélectionnée. Veuillez choisir votre société de travail.');
        }

        // Clients + produits liés à cette société active
        // Assurez-vous que les clés étrangères dans Client et Produit sont 'societe_id'
        $clients = Client::where('societe_id', $societeId)->get();
        $produits = Produit::where('societe_id', $societeId)->get();

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
            'societeId' => $societeId,
        ]);
    }

    /**
     * Stocke un nouveau document.
     */
    public function store(Request $request)
    {
        $societeId = session('current_societe_id');

        if (!$societeId) {
             return back()->withErrors('Erreur de contexte de société. Réessayez après avoir sélectionné une société.');
        }

        //  On n'a pas besoin de valider 'societe_id' dans le formulaire si on l'injecte depuis la session
        $validated = $request->validate([
            'code_document' => 'required|string|max:50|unique:en_tete_documents,code_document',
            'type_document' => 'required|in:facture,devis,avoir',
            'date_document' => 'required|date',
            'total_ht'      => 'required|numeric|min:0',
            'total_tva'     => 'required|numeric|min:0',
            'total_ttc'     => 'required|numeric|min:0',
            'client_code'   => 'required|string|exists:clients,code_cli',
            'lignes'        => 'required|array|min:1',
            'lignes.*.produit_code'     => 'required|string', // Validation plus faible ici, le filtre est clé
            'lignes.*.description'      => 'nullable|string',
            'lignes.*.quantite'         => 'required|numeric|min:1',
            'lignes.*.prix_unitaire_ht' => 'required|numeric|min:0',
            'lignes.*.taux_tva'         => 'required|numeric|min:0',
            'lignes.*.total_ttc'        => 'required|numeric|min:0',
            'statut'                    => 'nullable|string',
        ]);
        
        $typeMap = [
            'facture' => 'F',
            'devis'   => 'D',
            'avoir'   => 'A',
        ];

        $typeDocument = $typeMap[$validated['type_document']] ?? null;

        // SÉCURITÉ : Récupérer le client et s'assurer qu'il appartient à la société active
        $client = Client::where('code_cli', $validated['client_code'])
                        ->where('societe_id', $societeId)
                        ->firstOrFail();

        // Créer le document avec l'ID de la société de la session
        $document = EnTeteDocument::create([
            'societe_id'    => $societeId, 
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

        // Créer les lignes
        foreach ($validated['lignes'] as $ligne) {
            // SÉCURITÉ : Récupérer le produit et s'assurer qu'il appartient à la société active
            $produit = Produit::where('code_produit', $ligne['produit_code'])
                              ->where('societe_id', $societeId)
                              ->firstOrFail();

            LigneDocument::create([
                'document_id'      => $document->id,
                'produit_id'       => $produit->id,
                'description'      => $ligne['description'] ?? '',
                'quantite'         => $ligne['quantite'],
                'prix_unitaire_ht' => $ligne['prix_unitaire_ht'],
                'taux_tva'         => $ligne['taux_tva'],
                'total_ttc'        => $ligne['total_ttc'],
            ]);
        }
        
        // Redirection
        return redirect()
            ->route('documents.index', ['type' => $validated['type_document']])
            ->with('success', ucfirst($validated['type_document']) . ' créé avec succès.');
    }

    /**
     * Formulaire d’édition
     */
    public function edit($id)
    {
        $societeId = session('current_societe_id');
        
        // SÉCURITÉ : Récupérer le document en filtrant par la société active
        $document = EnTeteDocument::with('lignes.produit')
            ->where('societe_id', $societeId)
            ->findOrFail($id);

        $idSociete = $document->societe_id;

        //  Filtrage des clients et produits par la société active (pour les listes déroulantes de la vue)
        $clients = Client::where('societe_id', $idSociete)->get();
        $produits = Produit::where('societe_id', $idSociete)->get();

        $view = match ($document->type_document) {
            'F' => 'factures.edit',
            'A' => 'avoirs.edit',
            default => 'devis.edit',
        };
        
        if (request()->ajax()) {
            return view($view, compact('document', 'clients', 'produits'));
        }

        return view($view, compact('document', 'clients', 'produits'));
    }

    /**
     * Mise à jour d’un document
     */
    public function update(Request $request, $id)
    {
        $societeId = session('current_societe_id');

        // SÉCURITÉ : Récupérer le document en filtrant par la société active
        $document = EnTeteDocument::with('lignes')
            ->where('societe_id', $societeId)
            ->findOrFail($id);

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
            'lignes' => 'sometimes|array',
            'lignes.*.id' => 'nullable|exists:ligne_documents,id',
            'lignes.*.produit_code' => 'required|string',
            'lignes.*.description'  => 'nullable|string',
            'lignes.*.quantite'     => 'required|numeric|min:1',
            'lignes.*.prix_unitaire_ht' => 'required|numeric|min:0',
            'lignes.*.taux_tva'         => 'required|numeric|min:0',
            'lignes.*.total_ttc'        => 'required|numeric|min:0',
        ]);

        // Mise à jour de l'en-tête
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
                //  SÉCURITÉ : Trouver le produit en filtrant par société
                $produit = Produit::where('code_produit', $ligneData['produit_code'])
                                  ->where('societe_id', $societeId)
                                  ->firstOrFail(); // Échoue si le produit n'appartient pas à la société

                if (isset($ligneData['id'])) {
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
        $societeId = session('current_societe_id');
        
        // SÉCURITÉ : Récupérer et détruire le document en filtrant par la société active
        $document = EnTeteDocument::where('societe_id', $societeId)->findOrFail($id);
        
        $type = $document->type_document;
        $document->delete();

        $typeReverseMap = [
            'F' => 'facture',
            'D' => 'devis',
            'A' => 'avoir',
        ];
        $typeTexte = $typeReverseMap[$type] ?? 'devis';

        return redirect()
            ->route('documents.index', ['type' => $typeTexte])
            ->with('success', ucfirst($typeTexte) . ' supprimé avec succès.');
    }
}