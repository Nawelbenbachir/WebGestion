<form action="{{ route('clients.update', $client->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        {{--  Colonne gauche --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label for="code_cli" class="form-label">Code client</label>
                <input type="text" name="code_cli" class="form-control"
                       value="{{ old('code_cli', $client->code_cli) }}" required>
            </div>
            <div class="mb-3">
                <label for="code_comptable" class="form-label">Code comptable</label>
                <input type="text" name="code_comptable" class="form-control"
                       value="{{ old('code_comptable', $client->code_comptable) }}">
            </div>
            <div class="mb-3">
                <label for="societe" class="form-label">Société</label>
                <input type="text" name="societe" class="form-control"
                       value="{{ old('societe', $client->societe) }}">
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control"
                       value="{{ old('nom', $client->nom) }}" required>
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control"
                       value="{{ old('prenom', $client->prenom) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $client->email ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control"
                       value="{{ old('telephone', $client->telephone ?? '') }}">
            </div>
            <div class="mb-3">
                <label for="portable1" class="form-label">Portable 1</label>
                <input type="text" name="portable1" class="form-control"
                       value="{{ old('portable1', $client->portable1 ?? '') }}">
            </div>
            <div class="mb-3">
                <label for="portable2" class="form-label">Portable 2</label>
                <input type="text" name="portable2" class="form-control"    
                       value="{{ old('portable2', $client->portable2 ?? '') }}">    
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type de client</label>
                <select name="type" id="type" class="form-select">
                    <option value="">-- Sélectionner un type --</option>
                    <option value="particulier" {{ old('type', $client->type) == 'particulier' ? 'selected' : '' }}>Particulier</option>
                    <option value="artisan" {{ old('type', $client->type) == 'artisan' ? 'selected' : '' }}>Artisan</option>
                    <option value="entreprise" {{ old('type', $client->type) == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="reglement" class="form-label">Mode de règlement</label>
                <select name="reglement" id="reglement" class="form-select">
                    <option value="">-- Sélectionner un mode de règlement --</option>
                    <option value="virement" {{ ($client->reglement ?? '') === 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="cheque" {{ ($client->reglement ?? '') === 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="especes" {{ ($client->reglement ?? '') === 'especes' ? 'selected' : '' }}>Espèces</option>


                </select>
            </div>
        </div>

        {{--  Colonne droite --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label for="adresse1" class="form-label">Adresse</label>
                <textarea name="adresse1" class="form-control" rows="2">{{ old('adresse1', $client->adresse1 ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="adresse2" class="form-label">Adresse 2</label>
                <textarea name="adresse2" class="form-control" rows="2">{{ old('adresse2', $client->adresse2 ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="complement_adresse" class="form-label">Complément d'adresse</label>
                <textarea name="complement_adresse" class="form-control" rows="2">{{ old('complement_adresse', $client->complement_adresse ?? '') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" name="ville" class="form-control"
                           value="{{ old('ville', $client->ville ?? '') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="code_postal" class="form-label">Code Postal</label>
                    <input type="text" name="code_postal" class="form-control"
                           value="{{ old('code_postal', $client->code_postal ?? '') }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Boutons --}}
    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </div>
</form>
