@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier le produit</h2>

    <form action="{{ route('produits.update', $produit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="designation" class="form-label">Désignation</label>
            <input type="text" name="designation" id="designation"
                class="form-control @error('designation') is-invalid @enderror"
                value="{{ old('designation', $produit->designation) }}" required>
            @error('designation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="prix_ht" class="form-label">Prix HT</label>
            <input type="number" name="prix_ht" id="prix_ht" step="0.01"
                class="form-control @error('prix_ht') is-invalid @enderror"
                value="{{ old('prix_ht', $produit->prix_ht) }}" required>
            @error('prix_ht')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tva" class="form-label">TVA (%)</label>
            <input type="number" name="tva" id="tva" step="0.01"
                class="form-control @error('tva') is-invalid @enderror"
                value="{{ old('tva', $produit->tva ?? 20) }}" required>
            @error('tva')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" id="stock"
                class="form-control @error('stock') is-invalid @enderror"
                value="{{ old('stock', $produit->stock ?? 0) }}" required>
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <input type="text" name="categorie" id="categorie"
                class="form-control @error('categorie') is-invalid @enderror"
                value="{{ old('categorie', $produit->categorie) }}">
            @error('categorie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
        <a href="{{ route('produits.index') }}" class="btn btn-secondary">⬅ Retour</a>
    </form>
</div>
@endsection
