@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🧾 Créer un nouveau document</h2>

    {{-- ⚠️ Affichage des erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erreurs détectées :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🧾 Formulaire principal --}}
    <form action="{{ route('documents.store') }}" method="POST">
        @csrf

        {{-- 🔹 Informations générales --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Informations générales</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="date_document" class="form-label">Date du document</label>
                    <input type="date" class="form-control" name="date_document"
                           value="{{ old('date_document', date('Y-m-d')) }}">
                </div>

                <div class="mb-3">
                    <label for="client_code" class="form-label">Client</label>
                    <input list="clients" name="client_code" id="client_code" class="form-control" required
                           placeholder="Tapez le nom ou le code du client...">
                    <datalist id="clients">
                        @foreach ($clients as $client)
                            <option value="{{ $client->code_cli }}">
                                {{ $client->societe }} ({{ $client->code_cli }})
                            </option>
                        @endforeach
                    </datalist>
                </div>
            </div>
        </div>

        {{-- 🔹 Lignes du document --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <strong>Lignes du document</strong>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="lignesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix HT (€)</th>
                            <th>TVA (%)</th>
                            <th>Total TTC (€)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input list="produits" name="lignes[0][produit_code]" class="form-control produit-input"
                                       placeholder="Tapez le nom ou le code du produit..." required>
                            </td>
                            <td><input type="number" name="lignes[0][quantite]" class="form-control quantite" value="1" min="1"></td>
                            <td><input type="number" name="lignes[0][prix_unitaire_ht]" class="form-control prix" step="0.01"></td>
                            <td><input type="number" name="lignes[0][taux_tva]" class="form-control tva" step="0.1"></td>
                            <td><input type="number" name="lignes[0][total_ttc]" class="form-control total" step="0.01" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm removeRow">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <datalist id="produits">
                    @foreach ($produits as $produit)
                        <option value="{{ $produit->code_produit }}"
                                data-prix="{{ $produit->prix }}"
                                data-tva="{{ $produit->tva }}"
                                data-designation="{{ $produit->description }}">
                            {{ $produit->description }} ({{ $produit->code_produit }})
                        </option>
                    @endforeach
                </datalist>

                <button type="button" class="btn btn-success mt-3" id="addRow">➕ Ajouter une ligne</button>
            </div>
        </div>

        {{-- 🔹 Boutons d’action --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">⬅️ Annuler</a>
            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </div>
    </form>
</div>

{{--  Script dynamique pour produits + calculs + lignes --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;

    // Crée un dictionnaire JS des produits
    const produits = {
        @foreach($produits as $produit)
            "{{ $produit->code_produit }}": { 
                prix: {{ $produit->prix }},
                tva: {{ $produit->tva }}
            },
        @endforeach
    };

    // Calcul du total TTC pour une ligne donnée
    function updatePrixTTC(row) {
        const quantite = parseFloat(row.querySelector('.quantite').value) || 0;
        const prix = parseFloat(row.querySelector('.prix').value) || 0;
        const tva = parseFloat(row.querySelector('.tva').value) || 0;
        row.querySelector('.total').value = (prix * quantite * (1 + tva / 100)).toFixed(2);
    }

    // Gère la sélection d’un produit
    function handleProduitChange(input) {
        const row = input.closest('tr');
        const code = input.value;
        if (produits[code]) {
            row.querySelector('.prix').value = produits[code].prix.toFixed(2);
            row.querySelector('.tva').value = produits[code].tva;
            updatePrixTTC(row);
        }
    }

    // Gère les changements sur toute la table
    document.querySelector('#lignesTable').addEventListener('input', function(e) {
        const row = e.target.closest('tr');
        if (e.target.classList.contains('produit-input')) handleProduitChange(e.target);
        if (['quantite', 'prix', 'tva'].some(cls => e.target.classList.contains(cls))) updatePrixTTC(row);
    });

    // Bouton : ajouter une ligne
    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = document.querySelector('#lignesTable tbody');
        const newRow = tbody.querySelector('tr').cloneNode(true);

        newRow.querySelectorAll('input').forEach(el => {
            el.value = '';
            const name = el.getAttribute('name');
            if (name) el.name = name.replace(/\[\d+\]/, `[${rowIndex}]`);
        });

        tbody.appendChild(newRow);
        rowIndex++;
    });

    // Supprimer une ligne
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            const row = e.target.closest('tr');
            const rows = document.querySelectorAll('#lignesTable tbody tr');
            if (rows.length > 1) row.remove();
        }
    });
});
</script>
@endsection
