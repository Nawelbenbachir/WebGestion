<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnTeteDocument; 
use App\Http\Resources\EnteteDocumentResource;
use Illuminate\Support\Facades\Auth;

class EnteteDocumentController extends Controller
{
    /**
     * Liste de TOUS les documents de la société (Vue Index Mobile)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        $societeId = $request->header('X-Societe-Id') 
                     ?? $request->get('societe_id') 
                     ?? $user?->current_societe_id;

        if (!$societeId) {
            return response()->json(['message' => 'Société non spécifiée.'], 400);
        }

        // On prépare la requête de base
        $query = EnTeteDocument::with(['client', 'societe'])
            ->where('societe_id', $societeId);

        // FILTRE OPTIONNEL : 
        // Si vous tapez ?type=facture, on filtre. 
        // Sinon (si vide), on récupère tout sans distinction.
        $typeMap = ['facture' => 'F', 'devis' => 'D', 'avoir' => 'A'];
        $typeParam = $request->get('type');
        
        if ($typeParam && isset($typeMap[$typeParam])) {
            $query->where('type_document', $typeMap[$typeParam]);
        }

        // On récupère tout, trié par date
        $documents = $query->orderBy('date_document', 'desc')->get();

        return EnteteDocumentResource::collection($documents);
    }

    /**
     * Détail d'un document spécifique
     */
    public function show(string $id)
    {
        $document = EnTeteDocument::with(['client', 'societe', 'lignes'])
            ->findOrFail($id);

        return new EnteteDocumentResource($document);
    }
}