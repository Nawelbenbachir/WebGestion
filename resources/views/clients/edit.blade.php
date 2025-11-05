@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Modifier un client</h2>

    {{-- Affichage des messages d’erreur --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Attention !</strong> Des erreurs sont survenues :<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire de mise à jour --}}
    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- important pour dire à Laravel que c’est une mise à jour --}}

        <div class="mb-3">
            <label for="nom" class="form-label">Nom du client</label>
            <input type="text" name="nom" class="form-control"
                   value="{{ old('nom', $client->societe ) }}" required>
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
            <label for="type" class="form-label">Type de client</label>
            <select name="type" id="type" class="form-select">
                <option value="">-- Sélectionner un type --</option>
                <option value="particulier" {{ old('type', $client->type) == 'particulier' ? 'selected' : '' }}>Particulier</option>
                <option value="artisan" {{ old('type', $client->type) == 'artisan' ? 'selected' : '' }}>Artisan</option>
                <option value="entreprise" {{ old('type', $client->type) == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Mode de règlement</label>
            <select name="reglement" id="reglement" class="form-select">
                <option value="">-- Sélectionner un mode de règlement --</option>
                <option value="virement" {{ old('reglement', $client->reglement) == 'virement' ? 'selected' : '' }}>Virement</option>
                <option value="cheques" {{ old('reglement', $client->reglement) == 'cheques' ? 'selected' : '' }}>Chèques</option>
                <option value="especes" {{ old('reglement', $client->reglement) == 'especes' ? 'selected' : '' }}>Espèces</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="adresse1" class="form-label">Adresse</label>
            <textarea name="adresse1" class="form-control" rows="3">{{ old('adresse1', $client->adresse1) ?? ''}}</textarea>
        </div>
        <div class="mb-3">
            <label for="adresse2" class="form-label">Adresse2</label>
            <textarea name="adresse2" class="form-control" rows="3">{{ old('adresse2', $client->adresse2) ?? ''}}</textarea>
        </div>
        <div class="mb-3">
            <label for="complement_adresse" class="form-label">Complément d'adresse</label>
            <textarea name="complement_adresse" class="form-control" rows="3">{{ old('complement_adresse', $client->complement_adresse) ?? ''}}</textarea>
        </div>
         <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" name="ville" class="form-control"
                   value="{{ old('ville', $client->ville ?? '') }}">
        </div>
         <div class="mb-3">
            <label for="code_postal" class="form-label">Code Postal</label>
            <input type="text" name="code_postal" class="form-control"
                   value="{{ old('code_postal', $client->code_postal ?? '') }}">
        </div>
        <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </form>
</div>
@endsection
