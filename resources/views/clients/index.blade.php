@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Liste des clients</h2>

    <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">➕ Ajouter un client</a>

    @if($clients->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->nom }}</td>
                        <td>{{ ucfirst($client->type) }}</td>
                        <td>{{ $client->email ?? '—' }}</td>
                        <td>{{ $client->telephone ?? '—' }}</td>
                        <td>
                            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-info">Voir</a>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce client ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $clients->links() }} {{-- pagination --}}
    @else
        <p>Aucun client enregistré.</p>
    @endif
</div>
@endsection