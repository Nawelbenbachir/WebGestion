@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🧾 Créer un nouveau document</h2>

    {{--  Affichage des erreurs --}}
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

    {{--  Formulaire principal --}}
    <form action="{{ route('documents.store') }}" method="POST">
        @csrf

        {{--  Informations générales --}}
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

        {{-- Lignes du document --}}
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                               <input list="produits" class="form-control produit-input" placeholder="Choisir un produit...">
                            </td>
                            <td><input type="number" name="lignes[0][quantite]" class="form-control quantite" value="1" min="1"></td>
                            <td><input type="number" name="lignes[0][prix_unitaire_ht]" class="form-control prix" step="0.01" readonly ></td>
                            <td><input type="number" name="lignes[0][taux_tva]" class="form-control tva" step="0.1" readonly></td>
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
                                data-prix="{{ $produit->prix_ht }}"
                                data-tva="{{ $produit->tva }}"
                                data-designation="{{ $produit->description }}">
                            {{ $produit->description }} ({{ $produit->code_produit }})
                        </option>
                    @endforeach
                </datalist>

                <button type="button" class="btn btn-success mt-3" id="addRow">➕ Ajouter une ligne</button>
            </div>
        </div>

        {{-- Boutons d’action --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">⬅️ Annuler</a>
            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </div>
    </form>
</div>

{{--  Script dynamique pour produits + calculs + lignes --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
     const tbody = document.querySelector('#lignesTable tbody');
    const datalist = document.getElementById('produits');
    const addRowBtn = document.getElementById('addRow');


    function updatePrixTTC(row) {
        const qte = parseFloat(row.querySelector('.quantite').value) || 0;
        const prix = parseFloat(row.querySelector('.prix').value) || 0;
        const tva = parseFloat(row.querySelector('.tva').value) || 0;
        const total = prix * qte * (1 + tva / 100);
        row.querySelector('.total').value = total.toFixed(2);
    }

    lignesTable.addEventListener('input', (e) => {
        const row = e.target.closest('tr');

        //  Quand on sélectionne un produit
        if (e.target.classList.contains('produit-input')) {
            const code = e.target.value.trim();
            const option = Array.from(datalist.options).find(opt => opt.value === code);

            if (option) {
                row.querySelector('.prix').value = option.dataset.prix;
                row.querySelector('.tva').value = option.dataset.tva;
                updatePrixTTC(row);
            } else {
                // Réinitialisation si code inconnu
                row.querySelector('.prix').value = '';
                row.querySelector('.tva').value = '';
                row.querySelector('.total').value = '';
            }
        }

        if (e.target.classList.contains('quantite') || e.target.classList.contains('tva')) {
            updatePrixTTC(row);
        }
    });
        addRowBtn.addEventListener('click', () => {
        const firstRow = tbody.querySelector('tr');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.produit-input').value = '';
        newRow.querySelector('.quantite').value = 1;
        newRow.querySelector('.prix').value = '';
        newRow.querySelector('.tva').value = '';
        newRow.querySelector('.total').value = '';

        tbody.appendChild(newRow);
        updateAllNames();
    }); 
//Met à jour les noms des inputs pour chaque ligne
    function updateAllNames() {
    tbody.querySelectorAll('tr').forEach((row, i) => {
        row.querySelector('.produit-input').setAttribute('name', `lignes[${i}][produit_code]`);
        row.querySelector('.quantite').setAttribute('name', `lignes[${i}][quantite]`);
        row.querySelector('.prix').setAttribute('name', `lignes[${i}][prix_unitaire_ht]`);
        row.querySelector('.tva').setAttribute('name', `lignes[${i}][taux_tva]`);
        row.querySelector('.total').setAttribute('name', `lignes[${i}][total_ttc]`);
    });
}
});
</script>

@endsection
