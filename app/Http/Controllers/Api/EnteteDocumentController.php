<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnTeteDocument; 
use App\Http\Resources\EnteteDocumentResource;
use Illuminate\Support\Facades\Auth;

class EnteteDocumentController extends Controller
{
  
    public function index(Request $request)
    {
         $user = Auth::user();
        // if (!$user) {
        //     return response()->json(['message' => 'Non authentifié.'], 401);
        // }

        // $societeId = $request->header('X-Societe-Id') 
        //              ?? $request->get('societe_id') 
        //              ?? $user?->current_societe_id;

        // // if (!$societeId) {
        // //     return response()->json([
        // //         'error' => 'Aucune société spécifiée',
        // //         'message' => 'Veuillez fournir un societe_id (URL), un X-Societe-Id (Header) ou être rattaché à une société.'
        // //     ], 400);
        // // }
        //tester l'api sans sanctum
         $societeId = $request->get('societe_id') ?? 1;

        // On prépare la requête de base
        $query = EnTeteDocument::with(['client', 'societe'])
            ->where('societe_id', $societeId);

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->get('client_id'));
        }

        // Recherche par numéro de document ou nom du client
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code_document', 'like', "%{$search}%")
                  ->orWhereHas('client', function($queryClient) use ($search) {
                      $queryClient->where('nom', 'like', "%{$search}%");
                  });
            });
        }
        
        $typeMap = ['facture' => 'F', 'devis' => 'D', 'avoir' => 'A'];
        $typeParam = $request->get('type');
        
        if ($typeParam && isset($typeMap[$typeParam])) {
            $query->where('type_document', $typeMap[$typeParam]);
        }
        //  Calcul des totaux de la sélection (avant pagination)
        $totalTTC = (float) $query->sum('total_ttc');
        $count = $query->count();

        
        
        $documents = $query->orderBy('date_document', 'desc')->get()        ;

       
        //  Retour avec métadonnées personnalisées
        return EnteteDocumentResource::collection($documents)
            ->resolve();
    }

    /**
     * Détail d'un document spécifique
     */
    public function show(string $id)
    {
        $user = Auth::user();
    
        // On récupère le document  s'il appartient à la société de l'utilisateur
        // $document = EnTeteDocument::with(['client', 'societe', 'lignes'])
        //     ->where('societe_id', $user->current_societe_id) 
        //     ->findOrFail($id);
            
        //tester api sans sanctum 
         $document = EnTeteDocument::with(['client', 'societe', 'lignes'])->findOrFail($id);
        
        return new EnteteDocumentResource($document);
    }
}