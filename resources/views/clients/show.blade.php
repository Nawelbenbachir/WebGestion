@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Détails du client</h2>

    <div class="card p-3 shadow-sm">
        <p><strong>Nom :</strong> {{ $client->nom }}</p>
        <p><strong>Prénom :</strong> {{ $client->prenom ?? '-' }}</p>
        <p><strong>Type :</strong> {{ ucfirst($client->type) }}</p>
        <p><strong>Mode de règlement :</strong> {{ $client->reglement ?? '-' }}</p>
        <p><strong>Email :</strong> {{ $client->email ?? '-' }}</p>
        <p><strong>Téléphone :</strong> {{ $client->telephone ?? '-' }}</p>
        <p><strong>Adresse :</strong> {{ $client->adresse ?? '-' }}</p>
        <p><strong>SIRET :</strong> {{ $client->siret ?? '-' }}</p>
        <p><strong>IBAN :</strong> {{ $client->iban ?? '-' }}</p>
    </div>

    <a href="{{ route('clients.index') }}" class="btn btn-secondary mt-3">⬅ Retour à la liste</a>
</div>
@endsection