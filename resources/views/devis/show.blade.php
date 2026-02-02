<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>

<div class="container mt-4">
    <h2 class="mb-4">📄 Détails du document n° {{ $document->code_document }}</h2>

    {{-- Informations générales --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Informations générales</strong>
        </div>
        <div class="card-body">
            <p><strong>Type de document :</strong> {{ ucfirst($document->type_document) }}</p>
            <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}</p>
            <p><strong>Client :</strong> {{ $document->client_nom }} ({{ $document->client_code }})</p>
            <p><strong>Email :</strong> {{ $document->email }}</p>
            <p><strong>Téléphone :</strong> {{ $document->telephone }}</p>
            <p><strong>Adresse :</strong> {{ $document->adresse }}</p>
        </div>
    </div>

    {{-- Lignes du document --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <strong>Détails des lignes</strong>
        </div>
        <div class="card-body">
            @if($document->lignes->isEmpty())
                <div class="alert alert-warning">Aucune ligne associée à ce document.</div>
            @else
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>PU HT (€)</th>
                            <th>TVA (%)</th>
                            <th>Total HT (€)</th>
                            <th>Total TTC (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($document->lignes as $ligne)
                            <tr>
                                <td>{{ $ligne->designation }}</td>
                                <td class="text-center">{{ $ligne->quantite }}</td>
                                <td class="text-end">{{ number_format($ligne->prix_unitaire_ht, 2, ',', ' ') }}</td>
                                <td class="text-center">{{ $ligne->taux_tva }}</td>
                                <td class="text-end">{{ number_format($ligne->total_ht, 2, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($ligne->total_ttc, 2, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Totaux --}}
    <div class="card">
        <div class="card-header bg-dark text-white">
            <strong>Récapitulatif</strong>
        </div>
        <div class="card-body">
            <p><strong>Total HT :</strong> {{ number_format($document->total_ht, 2, ',', ' ') }} €</p>
            <p><strong>Total TVA :</strong> {{ number_format($document->total_tva, 2, ',', ' ') }} €</p>
            <p><strong>Total TTC :</strong> {{ number_format($document->total_ttc, 2, ',', ' ') }} €</p>
        </div>
    </div>

    {{-- Boutons d’action --}}
    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">⬅️ Retour à la liste</a>
        <div>
            <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning">✏️ Modifier</a>

            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Supprimer ce document ?')">🗑️ Supprimer</button>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>
