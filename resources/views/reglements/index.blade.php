<!-- resources/views/reglements/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des règlements</h1>

    <a href="{{ route('reglements.create') }}" class="btn btn-primary">Ajouter un règlement</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Numero</th>
                <th>Document</th>
                <th>Montant</th>
                <th>Mode de paiement</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reglements as $reglement)
                <tr>
                    <td>{{ $reglement->id }}</td>
                    <td>{{ $reglement->document->code_document ?? '-' }}</td>
                    <td>{{ $reglement->montant }} €</td>
                    <td>{{ $reglement->mode_paiement }}</td>
                    <td>{{ $reglement->date_reglement }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection