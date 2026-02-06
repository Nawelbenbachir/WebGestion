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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class EnTeteDocumentController extends Controller
{
    /**
     * Affiche la liste des documents (filtrés par type et par société active).
     */
   public function index(Request $request, $type = null)
    {
        // 1. Récupération de la société active
        $societeId = session('current_societe_id');

        if (!$societeId) {
            return redirect()->route('dashboard')
                ->with('error', 'Veuillez sélectionner une société de travail pour afficher les documents.');
        }

        // 2. Détermination du type (Priorité au paramètre de route, sinon fallback sur query string)
        $type = $type ?? $request->get('type', 'devis');

        // 3. Mapping des codes pour la base de données
        $typeMap = [
            'facture' => 'F',
            'devis'   => 'D',
            'avoir'   => 'A',
        ];

        $typeCode = $typeMap[$type] ?? 'D';

        // 4. Récupération filtrée des documents
        $documents = EnTeteDocument::with(['societe', 'client'])
            ->where('type_document', $typeCode)
            ->where('societe_id', $societeId)
            ->orderBy('date_document', 'desc')
            ->orderBy('code_document', 'desc')
            ->get();

        // 5. Sélection de la vue dynamique
        $view = match ($type) {
            'facture' => 'facture.index',
            'avoir'   => 'avoir.index',
            default   => 'devis.index', // Par défaut pour 'devis' ou autre
        };
        
        return view($view, compact('documents', 'type'));
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
    $societeId = session('current_societe_id');

    if (!$societeId) {
        return back()->withErrors('Aucune société sélectionnée.');
    }

    // Mapping pour obtenir le code (F, D, A)
    $typeMap = ['facture' => 'F', 'devis' => 'D', 'avoir' => 'A'];
    $typeCode = $typeMap[$type] ?? 'D';

    
    $prochainNumero = $this->genererNumeroDocument($typeCode, $societeId);

    $clients = Client::where('id_societe', $societeId)->get();
    $produits = Produit::where('id_societe', $societeId)->get();

    $view = match ($type) {
        'facture' => 'facture.create',
        'avoir'   => 'avoir.create',
        default   => 'devis.create',
    };

    return view($view, [
        'type'           => $type,
        'clients'        => $clients,
        'produits'       => $produits,
        'societeId'      => $societeId,
        'prochainNumero' => $prochainNumero, 
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
            'code_document' => [
                'required',
                'string',
                Rule::unique('en_tete_documents')->where(fn ($query) => $query->where('societe_id', $societeId))
            ], 
            
            'type_document' => 'required|in:facture,devis,avoir',
            'date_document' => 'required|date',
            'date_validite' => 'nullable|date',
            'date_echeance' => 'nullable|date',
            'total_ht'      => 'required|numeric',
            'total_tva'     => 'required|numeric',
            'total_ttc'     => 'required|numeric',
            'client_id' => 'required|integer|exists:clients,id',
            'lignes'        => 'required|array|min:1',
            'lignes.*.produit_code'     => 'required|string', 
            'lignes.*.description'      => 'nullable|string',
            'lignes.*.quantite'         => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire_ht' => 'required|numeric',
            'lignes.*.taux_tva'         => 'required|numeric',
            'lignes.*.total_ttc'        => 'required|numeric',
            'statut'                    => 'nullable|string',
        ]);
       

        // Mapping du type pour la génération et le stockage
        $typeMap = [
            'facture' => 'F',
            'devis'   => 'D',
            'avoir'   => 'A',
        ];
        $typeCode = $typeMap[$validated['type_document']];

        //  Début de la transaction pour garantir l'intégrité des données
        try {
            return DB::transaction(function () use ($validated, $societeId, $typeCode) {
                
                // Récupération sécurisée du client
                $client = Client::where('id', $validated['client_id'])
                    ->where('id_societe', $societeId)
                    ->firstOrFail();

                // Création de l'en-tête
                $document = EnTeteDocument::create([
                    'societe_id'    => $societeId, 
                   'code_document' => $validated['code_document'],
                   'date_validite' => $validated['date_validite']?? null,
                   'date_echeance' => $validated['date_echeance']?? null,
                    'type_document' => $typeCode,
                    'date_document' => $validated['date_document'],
                    'total_ht'      => $validated['total_ht'],
                    'total_tva'     => $validated['total_tva'],
                    'total_ttc'     => $validated['total_ttc'],
                    'solde'         => $validated['total_ttc'], 
                    'client_id'     => $client->id,
                    'client_nom'    => $client->societe,
                    'adresse'       => $client->adresse1,
                    'telephone'     => $client->telephone,
                    'email'         => $client->email,
                    'statut'        => $validated['statut'] ?? 'brouillon',
                ]);

                // Création des lignes de détail
                foreach ($validated['lignes'] as $ligne) {
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

                return redirect()
                    ->route('documents.index', ['type' => $validated['type_document']])
                    ->with('success', ucfirst($validated['type_document']) . " " . $validated['code_document'] . " créé avec succès.");
            });

        } catch (\Exception $e) {
         
            Log::error("Erreur lors de la création du document : " . $e->getMessage());
            return back()->withInput()->withErrors("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
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
            'A' => 'avoir.edit',
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

        //  Récupérer le document en filtrant par la société active
        $document = EnTeteDocument::with('lignes')
            ->where('societe_id', $societeId)
            ->findOrFail($id);

        // Validation de l'en-tête + lignes
        $validated = $request->validate([
            'date_document' => 'required|date',
            'date_validite' => 'nullable|date',
            'date_echeance' => 'nullable|date',
            'statut'        => 'required|string|in:brouillon,envoye,paye',
            'client_id'    => 'required|exists:clients,id',
            'total_ht'      => 'required|numeric',
            'total_tva'     => 'required|numeric',
            'total_ttc'     => 'required|numeric',
            'commentaire'=> 'nullable|string',
            'lignes'        => 'sometimes|array',
            'lignes.*.id'   => 'nullable|exists:ligne_documents,id',
            'lignes.*.produit_code' => 'required|string',
            'lignes.*.description'  => 'nullable|string',
            'lignes.*.quantite'     => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire_ht' => 'required|numeric',
            'lignes.*.taux_tva'         => 'required|numeric',
            'lignes.*.total_ttc'        => 'required|numeric',
        ]);
        
        
    
        try {
            DB::beginTransaction();

            // Mise à jour de l'en-tête
            $document->update([
                'date_document' => $validated['date_document'],
                'date_validite' => $validated['date_validite'] ?? null,
                'date_echeance' => $validated['date_echeance'] ?? null,
                'statut'        => $validated['statut'],
                'client_id'     => $validated['client_id'],
                'total_ht'      => $validated['total_ht'],
                'total_tva'     => $validated['total_tva'],
                'total_ttc'     => $validated['total_ttc'],
                'solde'         => $validated['total_ttc'], 
                'commentaire'=> $validated['commentaire'] ?? null,
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
            // Ceci va stopper la redirection et afficher l'erreur précise (ex: colonne manquante)
    dd([
        'Message' => $e->getMessage(),
        'Fichier' => $e->getFile(),
        'Ligne' => $e->getLine()
    ]);
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

     private function genererNumeroDocument($type, $societeId)
    {
        //  Récupérer le format depuis la table parametres
        // On cherche directement dans la table parametres liée à la société
        $formatChoisi = DB::table('societes')
            ->where('id', $societeId)
            ->value('format_numero_document') ?? 'simple';

        $prefix = match($type) {
            'F' => 'F',
            'A' => 'A',
            'D' => 'D',
            default => 'DOC'
        };

        $annee = now()->format('Y');
        $mois = now()->format('m');

        //  Construction de la base de recherche
        $baseCode = ($formatChoisi === 'mensuel') 
            ? "{$prefix}-{$annee}-{$mois}-" 
            : "{$prefix}-{$annee}-";

        $nbTiretsAttendus = ($formatChoisi === 'mensuel') ? 3 : 2;

        $dernierDoc = EnTeteDocument::where('societe_id', $societeId)
            ->where('code_document', 'LIKE', $baseCode . '%')
            // On s'assure que le format correspond (filtrage par nombre de tirets)
            ->whereRaw("(LENGTH(code_document) - LENGTH(REPLACE(code_document, '-', ''))) = ?", [$nbTiretsAttendus])
            ->orderByRaw('LENGTH(code_document) DESC')
            ->orderBy('code_document', 'desc')
            ->first();

            $compteur = 1;

        if ($dernierDoc) {
            // Extraction du dernier segment (le numéro)
            $parties = explode('-', $dernierDoc->code_document);
            $dernierNombre = end($parties);
            if (is_numeric($dernierNombre)) {
                $compteur = (int)$dernierNombre + 1;
            }
        }

        // Formatage final (ex: F-2024-001 ou F-2024-05-001)
        return $baseCode . str_pad($compteur, 3, '0', STR_PAD_LEFT);
    }

    private function dupliquerDocument($id, $nouveauType, $messageSucces)
    {
        try {
            return DB::transaction(function () use ($id, $nouveauType, $messageSucces) {
                
                $original = EnTeteDocument::find($id);
                if (!$original) {
                    throw new \Exception("Document source introuvable.");
                }
                $multiplicateur = ($nouveauType === 'A') ? -1 : 1;

                // Vérification si la transformation a déjà eu lieu
                if ($nouveauType == 'F') {
                    $exist = EnTeteDocument::where('devis_id', $original->id)->first();
                    if ($exist) throw new \Exception("Une facture ({$exist->code_document}) existe déjà pour ce devis.");
                } elseif ($nouveauType == 'A') {
                    $exist = EnTeteDocument::where('facture_id', $original->id)->first();
                    if ($exist) throw new \Exception("Un avoir ({$exist->code_document}) existe déjà pour cette facture.");
                }
                $societeId = session('current_societe_id');
                $nouveauDoc = $original->replicate();
                $nouveauDoc->type_document = $nouveauType;
                $nouveauDoc->date_document = now();
                $nouveauDoc->statut = 'brouillon';
                $nouveauDoc->total_ht = $original->total_ht * $multiplicateur;
                $nouveauDoc->total_tva = $original->total_tva * $multiplicateur;
                $nouveauDoc->total_ttc = $original->total_ttc * $multiplicateur;
                
                // Appel de la nouvelle logique de numérotation
                $nouveauDoc->code_document = $this->genererNumeroDocument($nouveauType, $societeId);

                // Liaisons
                if ($nouveauType == 'F') {
                    $nouveauDoc->devis_id = $original->id;
                } elseif ($nouveauType == 'A') {
                    $nouveauDoc->facture_id = $original->id;
                }

                if (!$nouveauDoc->save()) {
                    throw new \Exception("Erreur lors de la création de l'en-tête.");
                }

                // Duplication des lignes
                $lignes = LigneDocument::where('document_id', $original->id)->get();
                foreach ($lignes as $ligne) {
                    $nouvelleLigne = $ligne->replicate();
                    $nouvelleLigne->document_id = $nouveauDoc->id;
                    $nouvelleLigne->prix_unitaire_ht = $ligne->prix_unitaire_ht * $multiplicateur;
                    $nouvelleLigne->total_ttc = $ligne->total_ttc * $multiplicateur;
                    $nouvelleLigne->save();
                }

                return redirect()->route('documents.index')->with('success', $messageSucces);
            });

        } catch (\Exception $e) {
            Log::error("Erreur duplication : " . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function downloadPdf($id)
{
    $document = EnTeteDocument::with(['lignes.produit', 'client', 'societe'])->findOrFail($id);

    // On prépare les données pour la vue PDF
    $data = [
        'document' => $document,
        'societe'  => $document->societe,
        'client'   => $document->client,
    ];
    $libelleType = match ($document->type_document) {
        'F' => 'Facture',
        'A' => 'Avoir',
        'D' => 'Devis',
    };
    // On charge une vue spécifique pour le design du PDF
    $pdf = Pdf::loadView('pdf.documents', $data + ['libelleType' => $libelleType]);

    // On force le téléchargement avec le nom de la facture
    return $pdf->download($libelleType . '_' . $document->code_document . '.pdf');
}
}