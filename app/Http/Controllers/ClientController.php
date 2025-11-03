<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients
     */
    public function index()
    {
        $clients = Client::orderBy('nom')->paginate(10); // pagination par 10
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
            'code_comptable'  => 'nullable|string|unique:clients,code_compta',
            'nom'       => 'nullable|string|max:255',          // facultatif
            'prenom'    => 'nullable|string|max:255',          // facultatif
            'societe'   => 'required|string|max:255',          // obligatoire
            'email'     => 'nullable|email|max:255',          // facultatif
            'telephone' => 'nullable|string|max:20',          // facultatif
            'type'      => 'required|in:particulier,artisan,entreprise', // obligatoire
            'adresse1'  => 'nullable|string',                 // facultatif
]);
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
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.show', compact('client'));
    }

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
        
            'nom'           => 'required|string|max:255',
            'type'          => 'required|string|in:particulier,artisan,entreprise',
            'email'         => 'nullable|email|unique:clients,email,' . $client->id,
            'telephone'     => 'nullable|string|max:20',
            'adresse1'       => 'nullable|string|max:255',
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
