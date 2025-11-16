
    <form id="facture-edit-form" action="{{ route('documents.update', $document->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Informations générales --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white"><strong>Informations générales</strong></div>
            <div class="card-body">
                <input type="hidden" name="societe_id" value="{{ $document->societe_id }}">
                <input type="hidden" name="type_document" value="{{ $document->type_document }}">
                <div class="mb-3">
                    <label for="date_document" class="form-label">Date du document</label>
                    <input type="date" class="form-control" name="date_document" value="{{ old('date_document', $document->date_document) }}">
                </div>

                <div class="mb-3">
                    <label for="client_code" class="form-label">Client</label>
                    <input list="clients" name="client_code" id="client_code" class="form-control" required
                        value="{{ old('client_code', $document->client->code_cli) }}">
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
            <div class="card-header bg-secondary text-white"><strong>Lignes du document</strong></div>
            <div class="card-body">
                <table class="table table-bordered" id="lignesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Description</th>
                            <th>Quantité</th>
                            <th>Prix HT (€)</th>
                            <th>TVA (%)</th>
                            <th>Total TTC (€)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($document->lignes as $i => $ligne)
                        <tr>
                            <td>
                                <input list="produits" class="form-control produit-input" name="lignes[{{ $i }}][produit_code]"
                                       value="{{ old("lignes.$i.produit_code", $ligne->produit_code) }}" placeholder="Choisir un produit...">
                            </td>
                            <td>
                                <input type="text" name="lignes[{{ $i }}][description]" class="form-control description" value="{{ old("lignes.$i.description", $ligne->description) }}" readonly>
                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][quantite]" class="form-control quantite" value="{{ old("lignes.$i.quantite", $ligne->quantite) }}" min="1">
                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][prix_unitaire_ht]" class="form-control prix" value="{{ old("lignes.$i.prix_unitaire_ht", $ligne->prix_ht) }}" step="0.01" readonly>
                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][taux_tva]" class="form-control tva" value="{{ old("lignes.$i.taux_tva", $ligne->taux_tva) }}" step="0.1" readonly>
                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][total_ttc]" class="form-control total" value="{{ old("lignes.$i.total_ttc", $ligne->total_ttc) }}" step="0.01" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm addRowBelow">➕</button>
                                <button type="button" class="btn btn-danger btn-sm removeRow">🗑️</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <datalist id="produits">
                    @foreach($produits as $produit)
                        <option value="{{ $produit->code_produit }}"
                                data-prix="{{ $produit->prix_ht }}"
                                data-tva="{{ $produit->tva }}"
                                data-designation="{{ $produit->description }}">
                            {{ $produit->description }} ({{ $produit->code_produit }})
                        </option>
                    @endforeach
                </datalist>

            </div>
        </div>

        {{-- Totaux --}}
        <div class="text-end mt-3">
            <strong>Total HT :</strong> <span id="display_total_ht">{{ number_format($document->total_ht,2,',',' ') }} €</span><br>
            <strong>Total TVA :</strong> <span id="display_total_tva">{{ number_format($document->total_tva,2,',',' ') }} €</span><br>
            <strong>Total TTC :</strong> <span id="display_total_ttc">{{ number_format($document->total_ttc,2,',',' ') }} €</span>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('documents.index', ['type' => $document->type_document]) }}" class="btn btn-secondary"> Retour</a>
            <button type="submit" class="btn btn-primary"> Enregistrer les modifications</button>
        </div>
    </form>
</div>



