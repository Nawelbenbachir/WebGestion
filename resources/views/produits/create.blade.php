@extends('layouts.app')

@section('content')
<div class="container">
    <h1> Ajouter un produit</h1>

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
            <label for="code" class="form-label">Code Produit</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
        </div>

        <div class="mb-3">
            <label for="prix_ht" class="form-label">Prix HT</label>
            <input type="text" name="prix_ht" id="prix_ht" class="form-control" value="{{ old('prix_ht') }}" required>
        </div>

        <div class="mb-3">
            <label for="tva" class="form-label">TVA</label>
            <select name="tva" id="tva" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <option value="normal" @selected(old('tva') == 'normal')>Normal</option>
                <option value="reduit10" @selected(old('tva') == 'reduit10')>Reduit 10</option>
                <option value="reduit5.5" @selected(old('tva') == 'reduit5.5')>Reduit 5.5</option>
                <option value="particulier" @selected(old('tva') == 'particulier')>Particulier</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="text" name="stock" id="stock" class="form-control" value="{{ old('stock') }}">
        </div>

        <button type="submit" class="btn btn-primary">✅ Enregistrer</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </form>
</div>
@endsection