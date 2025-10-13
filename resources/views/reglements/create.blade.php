@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>💰 Nouveau règlement</h2>

    {{-- Message de succès ou d'erreur --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erreur !</strong> Veuillez corriger les champs ci-dessous :
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reglements.store') }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label for="document_id" class="form-label">Document concerné</label>
            <select name="document_id" id="document_id" class="form-select" required>
                <option value="">-- Sélectionner un document --</option>
                @foreach($documents as $document)
                    <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                        {{ $document->code_document }} — {{ $document->client_nom ?? 'Client inconnu' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="mode_reglement" class="form-label">Mode de règlement</label>
            <select name="mode_reglement" id="mode_reglement" class="form-select" required>
                <option value="">-- Sélectionner un mode --</option>
                <option value="Espèces" {{ old('mode_reglement') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                <option value="Chèque" {{ old('mode_reglement') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                <option value="Virement" {{ old('mode_reglement') == 'Virement' ? 'selected' : '' }}>Virement</option>
                <option value="Carte Bancaire" {{ old('mode_reglement') == 'Carte Bancaire' ? 'selected' : '' }}>Carte Bancaire</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="montant" class="form-label">Montant (€)</label>
            <input type="number" step="0.01" name="montant" id="montant" class="form-control" value="{{ old('montant') }}" required>
        </div>

        <div class="mb-3">
            <label for="date_reglement" class="form-label">Date du règlement</label>
            <input type="date" name="date_reglement" id="date_reglement" class="form-control" value="{{ old('date_reglement') ?? date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="reference" class="form-label">Référence (facultatif)</label>
            <input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference') }}">
        </div>

        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        <a href="{{ route('reglements.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </form>
</div>
@endsection
