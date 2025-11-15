<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Parametre;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients
     */
    public function index()
    {
         // Récupérer l'ID de la dernière société
        $parametre = Parametre::first();
        $societeId = $parametre ? $parametre->derniere_societe : null;

        // Filtrer les clients de cette société seulement
        $clients = Client::when($societeId, function($query, $societeId) {
            return $query->where('id_societe', $societeId);
        })
        ->orderBy('nom')
        ->paginate(10);

        return view('clients.index', compact('clients'));
    }

    /**
     * Affiche le formulaire de création d’un client
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Enregistre un nouveau client dans la base
     */
    public function store(Request $request)
    {
        $validate=$request->validate([
            'code_cli' => 'nullable|string|unique:clients,code_cli',
            'code_comptable'  => 'nullable|string|unique:clients,code_comptable',
            'nom'       => 'nullable|string|max:255',          // facultatif
            'prenom'    => 'nullable|string|max:255',          // facultatif
            'societe'   => 'required|string|max:255',          // obligatoire
            'reglement'   => 'nullable|in:virement,cheques,especes', // facultatif
            'email'     => 'nullable|email|max:255',          // facultatif
            'telephone' => 'nullable|string|max:20',          // facultatif
            'type'      => 'required|in:particulier,artisan,entreprise', // obligatoire
            'portable1' => 'nullable|string|max:20',     // facultatif
            'portable2' => 'nullable|string|max:20',     // facultatif
            'adresse1'  => 'nullable|string',                 // facultatif
            'adresse2'  => 'nullable|string',                 // facultatif
            'complement_adresse'  => 'nullable|string',       // facultatif
            'code_postal'  => 'nullable|string|max:10',       // facultatif
            'ville'      => 'nullable|string|max:255',        // facultatif
        ]);
         // Récupérer l'id de la société depuis les paramètres
        $parametre = Parametre::first();
        $idSociete = $parametre ? $parametre->derniere_societe : null;

        if (!$idSociete) {
            return back()->withErrors('Aucune société sélectionnée dans les paramètres.');
        }

        // Ajouter l'id de la société aux données validées
        $validate['id_societe'] = $idSociete;

        if (empty($validate['code_cli'])) {
            $validate['code_cli'] = 'CLT' . strtoupper(substr($validate['nom'], 0, 3)) . rand(100, 999);
        }
                // Générer un code comptable si vide
        if (empty($validate['code_comptable'])) {

            $validate['code_comptable'] = 'CPT' . strtoupper(substr($validate['nom'] ?? '', 0, 1)) . now()->format('YmdHis');
        }
        Client::create($validate);

        return redirect()->route('clients.index')->with('success', '✅ Client ajouté avec succès.');
    }

    /**
     * Affiche le détail d’un client
     */
//     public function show($id)
//     {
//         $client = Client::findOrFail($id);
//         // Retourne uniquement le formulaire d'édition (sans layout complet)
//         return view('clients.edit', compact('client'));
// }

    /**
     * Affiche le formulaire d’édition d’un client
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Met à jour un client existant
     */
    public function update(Request $request, Client $client)
    {
      
        $validated = $request->validate([
            'code_cli'       => ['required', 'string', Rule::unique('clients')->ignore($client->id)],
            'code_comptable' => ['required', 'string', Rule::unique('clients')->ignore($client->id)],
            'societe'       => 'required|string|max:255',
            'nom'           => 'required|string|max:255',
            'prenom'        => 'nullable|string|max:255',
            'reglement'     => 'nullable|in:virement,cheque,especes', 
            'type'          => 'required|string|in:particulier,artisan,entreprise',
            'email'         => 'nullable|email|unique:clients,email,' . $client->id,
            'telephone'     => 'nullable|string|max:20',
            'portable1'     => 'nullable|string|max:20',
            'portable2'     => 'nullable|string|max:20',
            'adresse1'      => 'nullable|string|max:255',
            'adresse2'      => 'nullable|string|max:255',
            'complement_adresse' => 'nullable|string|max:255',
            'code_postal'   => 'nullable|string|max:10',
            'ville'         => 'nullable|string|max:255',
]);

        

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', '✅ Client mis à jour avec succès.');
    }

    /**
     * Supprime un client
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', '🗑️ Client supprimé avec succès.');
    }
}
