@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Détails du produit</h2>

    <div class="card p-3 shadow-sm">
        <p><strong>Désignation :</strong> {{ $produit->designation }}</p>
        <p><strong>Catégorie :</strong> {{ $produit->categorie ?? '-' }}</p>
        <p><strong>Prix HT :</strong> {{ number_format($produit->prix_ht, 2, ',', ' ') }} €</p>
        <p><strong>TVA :</strong> {{ $produit->tva ?? 20 }} %</p>
        <p><strong>Prix TTC :</strong> {{ number_format($produit->prix_ht * (1 + $produit->tva / 100), 2, ',', ' ') }} €</p>
        <p><strong>Stock disponible :</strong> {{ $produit->stock ?? 0 }}</p>
        <p><strong>Code barre :</strong> {{ $produit->code_barre ?? '-' }}</p>
    </div>

    <a href="{{ route('produits.index') }}" class="btn btn-secondary mt-3">⬅ Retour à la liste</a>
    <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-warning mt-3">✏️ Modifier</a>
</div>
@endsection
