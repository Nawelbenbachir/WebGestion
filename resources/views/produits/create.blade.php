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
    <form action="{{ route('produits.store') }}" method="POST">
        @csrf  <!-- sécurité obligatoire -->
        <div class="row">
            {{-- Colonne gauche --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="code_produit" class="form-label">Code produit</label>
                    <input type="text" name="code_produit" class="form-control"
                        value="{{ old('code_produit') ?: 'PRD' . strtoupper(substr(old('description') ?? '', 0, 3)) . rand(100, 999) }}">
                </div>

                <div class="mb-3">
                    <label for="code_comptable" class="form-label">Code comptable</label>
                     <input type="text" name="code_comptable" class="form-control"
                        value="{{ old('code_comptable') ?: 'CPT' . strtoupper(substr(old('nom') ?? '', 0, 1)) . now()->format('YmdHis') }}">
                </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}" required>
        </div>

        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <select name="categorie" id="categorie" class="form-select">
                <option value="">-- Sélectionner une catégorie --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('categorie') == $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nouvelle_categorie" class="form-label">Nouvelle catégorie (optionnelle)</label>
            <input type="text" name="nouvelle_categorie" id="nouvelle_categorie" class="form-control" value="{{ old('nouvelle_categorie') }}">
        </div>
        <div class="mb-3">
            <label for="prix_ht" class="form-label">Prix HT</label>
            <input type="text" name="prix_ht" id="prix_ht" class="form-control" value="{{ old('prix_ht') }}" required>
        </div>

        <div class="mb-3">
            <label for="tva" class="form-label">TVA</label>
            <select name="tva" id="tva" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <option value="20" @selected(old('tva') == '20')> 20</option>
                <option value="10" @selected(old('tva') == '10')> 10</option>
                <option value="5.5" @selected(old('tva') == '5.5')>5.5</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="text" name="stock" id="stock" class="form-control" value="{{ old('stock') }}">
        </div>

        <button type="submit" class="btn btn-primary">✅ Enregistrer</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">⬅️ Retour</a>
</div>
    </form>
</div>
@endsection