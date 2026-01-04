<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatistiqueResource;
use App\Models\LigneDocument;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StatistiqueController extends Controller
{
    public function ventesParProduitGlobal(int $idProduit)
    {
        // On récupère l'utilisateur. Auth::user() détecte 
        // soit la session (Navigateur), soit le Token (Postman)
        $user = Auth::user();

        // Si l'utilisateur n'est pas connecté, Laravel devrait déjà avoir redirigé
        // via le middleware, mais on laisse une sécurité JSON au cas où.
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $societeId = $user->last_active_societe_id ?? $user->societe_id;

        $stats = LigneDocument::select(
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite) as total_ht'),
                DB::raw('SUM(ligne_documents.prix_unitaire_ht * ligne_documents.quantite * (ligne_documents.taux_tva / 100)) as total_tva'),
                DB::raw('SUM(ligne_documents.total_ttc) as total_ttc'),
                DB::raw('SUM(ligne_documents.quantite) as total_quantite')
            )
            ->join('en_tete_documents', 'ligne_documents.document_id', '=', 'en_tete_documents.id')
            ->where('en_tete_documents.societe_id', $societeId)
            ->where('ligne_documents.produit_id', $idProduit)
            ->where('en_tete_documents.type_document', 'F')
            ->whereIn('en_tete_documents.statut', ['envoye', 'paye']) 
            ->first();

        $product = Produit::find($idProduit);

        if (!$stats || $stats->total_quantite === null) {
            $stats = (object) [
                'total_quantite' => 0, 'total_ht' => 0, 'total_tva' => 0, 'total_ttc' => 0,
            ];
        }

        $stats->id_produit = $idProduit;
        $stats->code_produit = $product?->code_produit;
        $stats->description_produit = $product?->description;

        return new StatistiqueResource($stats);
    }
}