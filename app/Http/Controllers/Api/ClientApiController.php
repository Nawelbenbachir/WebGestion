<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Resources\ClientResource;
use App\Http\Resources\EnteteDocumentResource;
use Illuminate\Support\Facades\Auth;

class ClientApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Récupérer les clients de la société connectée
        $societeId=$request->user()->last_active_societe_id;
          if(!$societeId){
            return response()->json(['error' => 'Aucune société active'], 400);
        }
    
        $clients=Client::where('id_societe',$societeId)->get();
            
        return ClientResource::collection($clients);
    }

  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //cas où on retourne le client avec ses documentirstOrFail();s associés 
        $client=Client::with('documents')->findOrFail($id);
        //si un type de document est spécifié en paramètre, on filtre les documents associés
        if(request()->filled('type')){
            $typeMap = ['facture' => 'F', 'devis' => 'D', 'avoir' => 'A'];
            $typeParam = request()->get('type');
            if (isset($typeMap[$typeParam])) {
                $client->setRelation('documents', $client->documents->where('type_document', $typeMap[$typeParam]));
            }
        }
        return new ClientResource($client);
    }

 
}
