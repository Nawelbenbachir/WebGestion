<?php

namespace App\Http\Controllers;

use App\Models\EnTeteDocument;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Produit;
use App\Models\LigneDocument;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'F' => 'facture.show',
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
        $clients = Client::where('id_societe', $societeId)->get();
        $produits = Produit::where('id_societe', $societeId)->get();

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

        $validated = $request->validate([
            'code_document' => 'required|string|max:50|unique:en_tete_documents,code_document',
            'type_document' => 'required|in:facture,devis,avoir',
            'date_document' => 'required|date',
            'total_ht'      => 'required|numeric|min:0',
            'total_tva'     => 'required|numeric|min:0',
            'total_ttc'     => 'required|numeric|min:0',
            'client_code'   => 'required|string|exists:clients,code_cli',
            'lignes'        => 'required|array|min:1',
            'lignes.*.produit_code'     => 'required|string', 
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

        // Récupérer le client et s'assurer qu'il appartient à la société active
        $client = Client::where('code_cli', $validated['client_code'])
                        ->where('id_societe', $societeId)
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
            'solde'         => $validated['total_ttc'], 
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
                              ->where('id_societe', $societeId)
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
        
        //  Récupérer le document en filtrant par la société active
        $document = EnTeteDocument::with('lignes.produit')
            ->where('societe_id', $societeId)
            ->findOrFail($id);

        $idSociete = $document->societe_id;

        //  Filtrage des clients et produits par la société active (pour les listes déroulantes de la vue)
        $clients = Client::where('id_societe', $idSociete)->get();
        $produits = Produit::where('id_societe', $idSociete)->get();

        $view = match ($document->type_document) {
            'F' => 'facture.edit',
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
            'lignes'        => 'sometimes|array',
            'lignes.*.id'   => 'nullable|exists:ligne_documents,id',
            'lignes.*.produit_code' => 'required|string',
            'lignes.*.description'  => 'nullable|string',
            'lignes.*.quantite'     => 'required|numeric|min:1',
            'lignes.*.prix_unitaire_ht' => 'required|numeric|min:0',
            'lignes.*.taux_tva'         => 'required|numeric|min:0',
            'lignes.*.total_ttc'        => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Mise à jour de l'en-tête
            $document->update([
                'date_document' => $validated['date_document'],
                'total_ht'      => $validated['total_ht'],
                'total_tva'     => $validated['total_tva'],
                'total_ttc'     => $validated['total_ttc'],
                'solde'         => $validated['total_ttc'], 
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
                    // SÉCURITÉ : Trouver le produit en filtrant par société
                    $produit = Produit::where('code_produit', $ligneData['produit_code'])
                                      ->where('id_societe', $societeId)
                                      ->firstOrFail();

                    if (isset($ligneData['id']) && !empty($ligneData['id'])) {
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

            DB::commit();

            // Mapping pour la redirection vers l'index filtré
            $typeReverseMap = [
                'F' => 'facture',
                'D' => 'devis',
                'A' => 'avoir',
            ];

            $typeTexte = $typeReverseMap[$document->type_document] ?? 'devis';

            // Redirection forcée vers l'index avec le paramètre de type
            return redirect()
                ->route('documents.index', ['type' => $typeTexte])
                ->with('success', ucfirst($typeTexte) . ' mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Une erreur est survenue : " . $e->getMessage());
        }
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
            ->route('documents.index', ['type' => $typeTexte]);
            // ->with('success', ucfirst($typeTexte) . ' supprimé avec succès.');
    }

   /**
     * Action publique : Devis -> Facture
     */
    public function transformerEnFacture($id)
    {
        return $this->dupliquerDocument($id, 'F', 'Le devis a été transformé en facture.');
    }

    /**
     * Action publique : Facture -> Avoir
     */
    public function transformerEnAvoir($id)
    {
        return $this->dupliquerDocument($id, 'A', 'L\'avoir a été généré à partir de la facture.');
    }

    /**
     * Méthode PRIVÉE générique pour éviter la répétition de code
     */
     private function dupliquerDocument($id, $nouveauType, $messageSucces)
    {
        try {
            // Utilisation d'une transaction pour garantir l'intégrité
            $resultat = DB::transaction(function () use ($id, $nouveauType) {
                
                // 1. Vérifier si le document source existe vraiment
                $original = EnTeteDocument::find($id);
                if (!$original) {
                    throw new \Exception("Document source introuvable (ID: $id)");
                }

                // 2. Duplication
                $nouveauDoc = $original->replicate();
                $nouveauDoc->type_document = $nouveauType;
                $nouveauDoc->date_document = now();
                
                // Vérifiez si votre DB accepte NULL pour le code_document
                // Si vous avez un trigger SQL ou un Observer, c'est ici qu'il agit
                $nouveauDoc->code_document = "TEMP-" . uniqid(); 

                if (!$nouveauDoc->save()) {
                    throw new \Exception("Échec de la sauvegarde de l'en-tête");
                }

                // 3. Duplication des lignes
               
                $lignes = LigneDocument::where('document_id', $original->id)->get();
                
                if ($lignes->isEmpty()) {
                    Log::warning("Le document $id n'a pas de lignes à copier.");
                }

                foreach ($lignes as $ligne) {
                    $nouvelleLigne = $ligne->replicate();
                    $nouvelleLigne->document_id = $nouveauDoc->id;
                    $nouvelleLigne->save();
                }

                return $nouveauDoc;
            });

            return redirect()->route('documents.index')->with('success', $messageSucces);

        } catch (\Exception $e) {
            // CRITIQUE : Loggez l'erreur pour la voir dans storage/logs/laravel.log
            Log::error("Erreur duplication document : " . $e->getMessage());
            
            // Retournez avec l'erreur réelle pour comprendre le problème
            return redirect()->back()->with('error', 'Erreur technique : ' . $e->getMessage());
        }
    }
}