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
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}">
        </div>

        <div class="mb-3">
            <label for="adresse1" class="form-label">Adresse</label>
            <input type="text" name="adresse1" id="adresse1" class="form-control" value="{{ old('adresse1') }}">
        </div>

        <button type="submit" class="btn btn-primary">✅ Enregistrer</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </form>
</div>
@endsection