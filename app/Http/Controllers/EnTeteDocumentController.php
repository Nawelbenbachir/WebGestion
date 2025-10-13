<?php

namespace App\Http\Controllers;

use App\Models\EnTeteDocument;
use Illuminate\Http\Request;

class EnTeteDocumentController extends Controller
{
    /**
     * Liste tous les en-têtes de documents
     */
    public function index()
    {
        $documents = EnTeteDocument::with(['societe', 'client'])->get();
        return response()->json($documents);
    }

    /**
     * Affiche un en-tête spécifique
     */
    public function show($id)
    {
        $document = EnTeteDocument::with(['societe', 'client'])->findOrFail($id);
        return response()->json($document);
    }

    /**
     * Crée un nouvel en-tête
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'societe_id'   => 'required|exists:societes,id',
            'code_document'=> 'required|string|max:50|unique:en_tete_documents,code_document',
            'type_document'=> 'required|in:facture,devis,avoir',
            'date_document'=> 'required|date',
            'total_ht'     => 'required|numeric|min:0',
            'total_tva'    => 'required|numeric|min:0',
            'total_ttc'    => 'required|numeric|min:0',
            'client_id'    => 'required|exists:clients,id',
            'client_nom'   => 'nullable|string|max:255',
            'logo'         => 'nullable|string',
            'adresse'      => 'nullable|string',
            'telephone'    => 'nullable|string|max:20',
            'email'        => 'nullable|email',
        ]);

        $document = EnTeteDocument::create($validated);

        return response()->json($document, 201);
    }

    /**
     * Met à jour un en-tête existant
     */
    public function update(Request $request, $id)
    {
        $document = EnTeteDocument::findOrFail($id);

        $validated = $request->validate([
            'type_document'=> 'in:facture,devis,avoir',
            'date_document'=> 'date',
            'total_ht'     => 'numeric|min:0',
            'total_tva'    => 'numeric|min:0',
            'total_ttc'    => 'numeric|min:0',
            'client_nom'   => 'nullable|string|max:255',
            'logo'         => 'nullable|string',
            'adresse'      => 'nullable|string',
            'telephone'    => 'nullable|string|max:20',
            'email'        => 'nullable|email',
        ]);

        $document->update($validated);

        return response()->json($document);
    }

    /**
     * Supprime un en-tête
     */
    public function destroy($id)
    {
       $table->foreign('document_id')
      ->references('id')->on('en_tete_documents')
      ->onDelete('cascade');

    }
}
