<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatistiqueResource;

use Illuminate\Http\Request;
use App\Models\LigneDocument;
use Illuminate\Support\Facades\DB;


class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
        public function ventesParProduitGlobal(int $idProduit)
    {
    // Récupère les stats pour le produit, toutes périodes confondues
       $stats = LigneDocument::select(
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite) as total_ht'),
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite * (ligne_documents.taux_tva / 100)) as total_tva'),
                DB::raw('SUM(ligne_documents.total_ttc) as total_ttc'),
                DB::raw('SUM(ligne_documents.quantite) as total_quantite')
            )
            ->join('en_tete_documents', 'ligne_documents.document_id', '=', 'en_tete_documents.id')
            
            // FILTRES SPÉCIFIQUES : Produit uniquement
            ->where('ligne_documents.produit_id', $idProduit)
            
            // FILTRES DE BASE : Type de document Facture (F) et Statut valide
            ->where('en_tete_documents.type_document', 'F')
            ->whereIn('en_tete_documents.statut', ['envoye', 'paye']) 
            ->first();
            
            if ($stats) {
            $stats->id_produit = $idProduit;
            $stats->annee = null;
            $stats->mois = null;
        } else {
            $stats = (object) [
                'total_quantite' => null,
                'id_produit' => $idProduit,
                'annee' => null,
                'mois' => null,
            ];
        }

        return new StatistiqueResource($stats);
    }
    public function ventesParPeriodeGlobal(int $annee, int $mois)
    {
         // Validation basique
        if ($mois < 1 || $mois > 12 || $annee < 2000) {
            return response()->json(['error' => 'Mois ou année invalide.'], 400);
        }
        
        $stats = LigneDocument::select(
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite) as total_ht'),
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite * (ligne_documents.taux_tva / 100)) as total_tva'),
                DB::raw('SUM(ligne_documents.total_ttc) as total_ttc'),
                DB::raw('SUM(ligne_documents.quantite) as total_quantite')
            )
            ->join('en_tete_documents', 'ligne_documents.document_id', '=', 'en_tete_documents.id')
            
            // FILTRES SPÉCIFIQUES : Année et Mois uniquement
            ->whereYear('en_tete_documents.date_document', $annee)
            ->whereMonth('en_tete_documents.date_document', $mois)
            
            // FILTRES DE BASE : Type de document Facture (F) et Statut valide
            ->where('en_tete_documents.type_document', 'F')
            ->whereIn('en_tete_documents.statut', ['envoye', 'paye']) 
            ->first();

        if ($stats) {
            // Attacher les paramètres de la route à l'objet de statistiques
            $stats->id_produit = null; // C'est une stat globale
            $stats->annee = $annee;
            $stats->mois = $mois;
        } else {
             // Crée un objet vide si la requête n'a rien trouvé
             $stats = (object) [
                 'total_quantite' => null, // Indique à la Resource que c'est vide
                 'id_produit' => null,
                 'annee' => $annee,
                 'mois' => $mois,
             ];
        }

        //  Retourner la Resource formatée
        return new StatistiqueResource($stats);
    }
    public function ventesParProduitEtMois(int $idProduit, int $annee, int $mois)
    {
        // 1. Validation basique
        if ($mois < 1 || $mois > 12 || $annee < 2000) {
            return response()->json(['error' => 'Mois ou année invalide.'], 400);
        }

        // 2. Requête Eloquent
        $stats = LigneDocument::select(
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite) as total_ht'),
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite * (ligne_documents.taux_tva / 100)) as total_tva'),
                DB::raw('SUM(ligne_documents.total_ttc) as total_ttc'),
                DB::raw('SUM(ligne_documents.quantite) as total_quantite')
            )
            ->join('en_tete_documents', 'ligne_documents.document_id', '=', 'en_tete_documents.id')
            
            // FILTRES SPÉCIFIQUES : Produit, Année, Mois
            ->where('ligne_documents.produit_id', $idProduit)
            ->whereYear('en_tete_documents.date_document', $annee)
            ->whereMonth('en_tete_documents.date_document', $mois)
            
            // FILTRES DE BASE : Type de document Facture (F) et Statut valide
            ->where('en_tete_documents.type_document', 'F')
            ->whereIn('en_tete_documents.statut', ['envoye', 'paye']) 
            ->first();

        //  Préparation pour la Resource
        if ($stats) {
            // Attacher les paramètres de la route à l'objet de statistiques
            $stats->id_produit = $idProduit;
            $stats->annee = $annee;
            $stats->mois = $mois;
        } else {
             // Crée un objet vide (stdClass) si la requête ne retourne rien, 
             // en attachant les paramètres pour que la Resource puisse les retourner avec des totaux à zéro.
             $stats = (object) [
                 'total_quantite' => null, // Indique à la Resource que c'est vide
                 'id_produit' => $idProduit,
                 'annee' => $annee,
                 'mois' => $mois,
             ];
        }

        //  Retourner la Resource formatée
        return new StatistiqueResource($stats);
    }

    

}
