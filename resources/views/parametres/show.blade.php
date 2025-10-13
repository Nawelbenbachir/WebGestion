@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Détails de la société</h2>

    <div class="card p-3">
        <p><strong>Code :</strong> {{ $societe->code_societe }}</p>
        <p><strong>Nom :</strong> {{ $societe->nom_societe }}</p>
        <p><strong>Email :</strong> {{ $societe->email }}</p>
        <p><strong>Adresse :</strong> {{ $societe->adresse1 }} {{ $societe->adresse2 }} {{ $societe->complement_adresse }}</p>
        <p><strong>Ville :</strong> {{ $societe->ville }}</p>
        <p><strong>Pays :</strong> {{ $societe->pays }}</p>
        <p><strong>SIRET :</strong> {{ $societe->siret }}</p>
        <p><strong>TVA :</strong> {{ $societe->tva }}</p>
        <p><strong>IBAN :</strong> {{ $societe->iban ?? '-' }}</p>
        <p><strong>SWIFT / BIC :</strong> {{ $societe->swift ?? '-' }}</p>

    </div>

    <a href="{{ route('societes.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
