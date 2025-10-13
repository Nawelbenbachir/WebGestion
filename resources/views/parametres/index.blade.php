@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Liste des sociétés</h2>

    <a href="{{ route('societes.create') }}" class="btn btn-primary mb-3">+ Nouvelle société</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Code</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Ville</th>
                <th>Pays</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($societes as $societe)
            <tr>
                <td>{{ $societe->code_societe }}</td>
                <td>{{ $societe->nom_societe }}</td>
                <td>{{ $societe->email }}</td>
                <td>{{ $societe->ville }}</td>
                <td>{{ $societe->pays }}</td>
                <td>
                    <a href="{{ route('societes.show', $societe->id) }}" class="btn btn-info btn-sm">Voir</a>
                    <a href="{{ route('societes.edit', $societe->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('societes.destroy', $societe->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette société ?')">Supprimer</button>
                    </form>
                </td>
                <td>
                    @if($societe->iban)
                    {{ substr($societe->iban, 0, 6) }}••••{{ substr($societe->iban, -4) }}
                    @else
                     —
                    @endif
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
