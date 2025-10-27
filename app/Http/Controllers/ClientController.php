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
        $validated = $request->validate([
            'code_societe'  => 'required|string|max:50',
            'code_client'   => 'required|string|max:50|unique:clients,code_client',
            'nom'           => 'required|string|max:255',
            'type'          => 'required|string|in:particulier,artisan,entreprise',
            'email'         => 'required|email|unique:clients,email',
            'telephone'     => 'nullable|string|max:20',
            'adresse'       => 'nullable|string|max:255',
            'ville'         => 'nullable|string|max:100',
            'code_postal'   => 'nullable|string|max:10',
            'pays'          => 'nullable|string|max:50',
        ]);

        Client::create($validated);

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
            'code_societe'  => 'required|string|max:50',
            'code_client'   => 'required|string|max:50|unique:clients,code_client,' . $client->id,
            'nom'           => 'required|string|max:255',
            'type'          => 'required|string|in:particulier,artisan,entreprise',
            'email'         => 'required|email|unique:clients,email,' . $client->id,
            'telephone'     => 'nullable|string|max:20',
            'adresse'       => 'nullable|string|max:255',
            'ville'         => 'nullable|string|max:100',
            'code_postal'   => 'nullable|string|max:10',
            'pays'          => 'nullable|string|max:50',
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
