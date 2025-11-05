@extends('layouts.app')

@section('content')
<div class="container">
    <h1> Ajouter un client</h1>

    <!-- Affichage des erreurs de validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf  <!-- sécurité obligatoire -->

        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" required>
        </div>

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
        </div>

        <div class="mb-3">
            <label for="societe" class="form-label">Société</label>
            <input type="text" name="societe" id="societe" class="form-control" value="{{ old('societe') }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type de client</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <option value="particulier" @selected(old('type') == 'particulier')>Particulier</option>
                <option value="artisan" @selected(old('type') == 'artisan')>Artisan</option>
                <option value="entreprise" @selected(old('type') == 'entreprise')>Entreprise</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="reglement" class="form-label">Mode de règlement</label>
            <select name="reglement" id="reglement" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <option value="virement" @selected(old('reglement') == 'virement')>Virement</option>
                <option value="cheques" @selected(old('reglement') == 'cheques')>Chèques</option>
                <option value="especes" @selected(old('reglement') == 'especes')>Espèces</option>
            </select>
        </div>


        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}">
        </div>
        <div class="mb-3">
            <label for="portable1" class="form-label">Portable</label>
            <input type="text" name="portable1" id="portable1" class="form-control" value="{{ old('portable1') }}">
        </div>
        <div class="mb-3">
            <label for="portable2" class="form-label">Portable2</label>
            <input type="text" name="portable2" id="portable2" class="form-control" value="{{ old('portable2') }}">
        </div>
        <div class="mb-3">
            <label for="adresse1" class="form-label">Adresse</label>
            <input type="text" name="adresse1" id="adresse1" class="form-control" value="{{ old('adresse1') }}">
        </div>
        <div class="mb-3">
            <label for="adresse2" class="form-label">Adresse2</label>
            <input type="text" name="adresse2" id="adresse2" class="form-control" value="{{ old('adresse2') }}">
        </div>
        <div class="mb-3">
            <label for="complement_adresse" class="form-label">Complément d'adresse</label>
            <input type="text" name="complement_adresse" id="complement_adresse" class="form-control" value="{{ old('complement_adresse') }}">
        </div>
        <div class="mb-3">
            <label for="code_postal" class="form-label">Code Postal</label>
            <input type="text" id="code_postal" name="code_postal" class="form-control">
        </div>

        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" id="ville" name="ville" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">✅ Enregistrer</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </form>
</div>
@endsection
