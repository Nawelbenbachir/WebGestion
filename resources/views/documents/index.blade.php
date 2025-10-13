@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📄 Liste des documents</h2>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Bouton pour créer un nouveau document --}}
    <div class="mb-3 text-end">
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            ➕ Nouveau document
        </a>
    </div>

    {{-- Vérifie s’il y a des documents --}}
    @if($documents->isEmpty())
        <div class="alert alert-info">
            Aucun document enregistré pour le moment.
        </div>
    @else
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Code Document</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Total TTC (€)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->code_document }}</td>
                        <td>{{ ucfirst($document->type_document) }}</td>
                        <td>{{ $document->date_document }}</td>
                        <td>{{ $document->client_nom }}</td>
                        <td class="text-end">{{ number_format($document->total_ttc, 2, ',', ' ') }}</td>
                        <td class="text-center">
                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-sm btn-info">
                                👁️ Voir
                            </a>
                            <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-sm btn-warning">
                                ✏️ Modifier
                            </a>
                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Supprimer ce document ?')">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination si activée --}}
        <div class="d-flex justify-content-center">
            {{ $documents->links() }}
        </div>
    @endif
</div>
@endsection
