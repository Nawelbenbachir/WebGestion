<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients (filtrés par société active).
     */
    public function index()
    {
        // Récupération de la société active à partir de la session
        $societeId = session('current_societe_id');

        if (!$societeId) {
            return redirect()->route('dashboard')->with('error', 'Veuillez sélectionner une société de travail pour afficher les clients.');
        }

        //  Filtrer les clients de cette société seulement
        $clients = Client::where('id_societe', $societeId)
            ->orderBy('nom')
            ->paginate(10);

        return view('clients.index', compact('clients'));
    }

    /**
     * Affiche le formulaire de création d’un client
     */
    public function create()
    {
        // On suppose que la vérification de societeId est faite dans un middleware ou sera faite à l'enregistrement
        return view('clients.create');
    }

    /**
     * Enregistre un nouveau client dans la base
     */
    public function store(Request $request)
    {
        //  Récupération de la société active à partir de la session
        $societeId = session('current_societe_id');

        if (!$societeId) {
            return back()->withErrors('Erreur : Aucune société de travail sélectionnée.');
        }

        //  SÉCURITÉ : Scoper les règles 'unique' à la société active
        $validate = $request->validate([
            // code_cli doit être unique DANS CETTE SOCIÉTÉ
            'code_cli' => [
                'nullable', 
                'string', 
                Rule::unique('clients', 'code_cli')->where(fn ($query) => $query->where('id_societe', $societeId)),
            ],
            // code_comptable doit être unique DANS CETTE SOCIÉTÉ
            'code_comptable' => [
                'nullable', 
                'string', 
                Rule::unique('clients', 'code_comptable')->where(fn ($query) => $query->where('id_societe', $societeId)),
            ],
            'nom'               => 'nullable|string|max:255',
            'prenom'            => 'nullable|string|max:255',
            'societe'           => 'required|string|max:255',
            'reglement'         => 'nullable|in:virement,cheques,especes',
            // L'email peut être unique par société (bien que moins courant, c'est plus sécuritaire)
            'email' => [
                'nullable', 
                'email', 
                'max:255',
                Rule::unique('clients', 'email')->where(fn ($query) => $query->where('id_societe', $societeId)),
            ],
            'telephone'         => 'nullable|string|max:20',
            'type'              => 'required|in:particulier,artisan,entreprise',
            'portable1'         => 'nullable|string|max:20',
            'portable2'         => 'nullable|string|max:20',
            'adresse1'          => 'nullable|string',
            'adresse2'          => 'nullable|string',
            'complement_adresse'=> 'nullable|string',
            'code_postal'       => 'nullable|string|max:10',
            'ville'             => 'nullable|string|max:255',
        ]);
        
        //  Ajouter l'id de la société active aux données validées
        $validate['id_societe'] = $societeId;

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
     * Affiche le formulaire d’édition d’un client
     */
    public function edit($id)
    {
        $societeId = session('current_societe_id');

        //  SÉCURITÉ : Récupérer le client seulement s'il appartient à la société active
        $client = Client::where('id_societe', $societeId)->findOrFail($id);
        
        return view('clients.edit', compact('client'));
    }

    /**
     * Met à jour un client existant
     */
    public function update(Request $request, Client $client)
    {
        $societeId = session('current_societe_id');

        //  SÉCURITÉ : Vérification IDOR (Insecure Direct Object Reference)
        if ($client->id_societe !== $societeId) {
            abort(403, 'Accès non autorisé à ce client.');
        }
        
        //  SÉCURITÉ : Scoper les règles 'unique' à la société active et ignorer l'ID du client
        $validated = $request->validate([
            // code_cli unique DANS CETTE SOCIÉTÉ, ignorer l'ID actuel
            'code_cli' => [
                'required', 
                'string', 
                Rule::unique('clients', 'code_cli')
                    ->where(fn ($query) => $query->where('id_societe', $societeId))
                    ->ignore($client->id),
            ],
            // code_comptable unique DANS CETTE SOCIÉTÉ, ignorer l'ID actuel
            'code_comptable' => [
                'required', 
                'string', 
                Rule::unique('clients', 'code_comptable')
                    ->where(fn ($query) => $query->where('id_societe', $societeId))
                    ->ignore($client->id),
            ],
            'societe'           => 'required|string|max:255',
            'nom'               => 'required|string|max:255',
            'prenom'            => 'nullable|string|max:255',
            'reglement'         => 'nullable|in:virement,cheques,especes',
            'type'              => 'required|string|in:particulier,artisan,entreprise',
            // Email unique DANS CETTE SOCIÉTÉ, ignorer l'ID actuel
            'email' => [
                'nullable',
                'email',
                Rule::unique('clients', 'email')
                    ->where(fn ($query) => $query->where('id_societe', $societeId))
                    ->ignore($client->id),
            ],
            'telephone'         => 'nullable|string|max:20',
            'portable1'         => 'nullable|string|max:20',
            'portable2'         => 'nullable|string|max:20',
            'adresse1'          => 'nullable|string|max:255',
            'adresse2'          => 'nullable|string|max:255',
            'complement_adresse' => 'nullable|string|max:255',
            'code_postal'       => 'nullable|string|max:10',
            'ville'             => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', ' Client mis à jour avec succès.');
    }

    /**
     * Supprime un client
     */
    public function destroy($id)
    {
        $societeId = session('current_societe_id');
        
        //  SÉCURITÉ : Récupérer et détruire le client en filtrant par la société active
        $client = Client::where('id_societe', $societeId)->findOrFail($id);
        
        $client->delete();

        return redirect()->route('clients.index')->with('success', ' Client supprimé avec succès.');
    }
}