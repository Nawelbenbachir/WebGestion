@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🧾 Créer un nouveau document</h2>

    {{-- Affichage des erreurs de validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>⚠️ Erreurs détectées :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire principal --}}
    <form action="{{ route('documents.store') }}" method="POST">
        @csrf

        {{-- Informations générales du document --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Informations générales</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="code_societe" class="form-label">Code société</label>
                    <input type="text" class="form-control" name="code_societe" value="{{ old('code_societe') }}" required>
                </div>

                <div class="mb-3">
                    <label for="code_document" class="form-label">Code document</label>
                    <input type="text" class="form-control" name="code_document" value="{{ old('code_document') }}" required>
                </div>

                <div class="mb-3">
                    <label for="type_document" class="form-label">Type de document</label>
                    <select name="type_document" class="form-control">
                        <option value="">-- Sélectionner --</option>
                        <option value="devis" {{ old('type_document') == 'devis' ? 'selected' : '' }}>Devis</option>
                        <option value="commande" {{ old('type_document') == 'commande' ? 'selected' : '' }}>Commande</option>
                        <option value="facture" {{ old('type_document') == 'facture' ? 'selected' : '' }}>Facture</option>
                        <option value="avoir" {{ old('type_document') == 'avoir' ? 'selected' : '' }}>Avoir</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="date_document" class="form-label">Date du document</label>
                    <input type="date" class="form-control" name="date_document" value="{{ old('date_document', date('Y-m-d')) }}">
                </div>

                <div class="mb-3">
                    <label for="client_code" class="form-label">Code client</label>
                    <input type="text" class="form-control" name="client_code" value="{{ old('client_code') }}" required>
                </div>

                <div class="mb-3">
                    <label for="client_nom" class="form-label">Nom du client</label>
                    <input type="text" class="form-control" name="client_nom" value="{{ old('client_nom') }}" required>
                </div>
            </div>
        </div>

        {{-- Lignes du document --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <strong>Lignes du document</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="lignesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>Prix HT (€)</th>
                            <th>TVA (%)</th>
                            <th>Total TTC (€)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="lignes[0][designation]" class="form-control" required></td>
                            <td><input type="number" name="lignes[0][quantite]" class="form-control" value="1" min="1"></td>
                            <td><input type="number" name="lignes[0][prix_unitaire_ht]" class="form-control" step="0.01"></td>
                            <td><input type="number" name="lignes[0][taux_tva]" class="form-control" value="20" step="0.1"></td>
                            <td><input type="number" name="lignes[0][total_ttc]" class="form-control" step="0.01" readonly></td>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow">🗑️</button></td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-success" id="addRow">➕ Ajouter une ligne</button>
            </div>
        </div>

        {{-- Boutons d’action --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">⬅️ Annuler</a>
            <button type="submit" class="btn btn-primary">💾 Enregistrer le document</button>
        </div>
    </form>
</div>

{{-- Script pour ajouter / supprimer des lignes dynamiquement --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;

    document.getElementById('addRow').addEventListener('click', function() {
        const tableBody = document.querySelector('#lignesTable tbody');
        const newRow = `
            <tr>
                <td><input type="text" name="lignes[${rowIndex}][designation]" class="form-control" required></td>
                <td><input type="number" name="lignes[${rowIndex}][quantite]" class="form-control" value="1" min="1"></td>
                <td><input type="number" name="lignes[${rowIndex}][prix_unitaire_ht]" class="form-control" step="0.01"></td>
                <td><input type="number" name="lignes[${rowIndex}][taux_tva]" class="form-control" value="20" step="0.1"></td>
                <td><input type="number" name="lignes[${rowIndex}][total_ttc]" class="form-control" step="0.01" readonly></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow">🗑️</button></td>
            </tr>`;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });
});
</script>
@endsection
