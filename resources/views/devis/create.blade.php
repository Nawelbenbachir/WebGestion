<form action="{{ route('documents.store') }}" method="POST" class="space-y-6">
    @csrf

    {{-- Informations générales --}}
    <input type="hidden" name="societe_id" value="{{ $parametre->derniere_societe ?? '' }}">
    <input type="hidden" name="type_document" value="{{ $type ?? 'devis' }}">
    <input type="hidden" name="code_document" value="{{ 'DOC' . strtoupper(uniqid()) }}">
    <input type="hidden" id="total_ht" name="total_ht" value="0">
    <input type="hidden" id="total_tva" name="total_tva" value="0">
    <input type="hidden" id="total_ttc" name="total_ttc" value="0">

    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Date --}}
            <div>
                <label for="date_document" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Date du document</label>
                <input type="date" name="date_document" id="date_document"
                       value="{{ old('date_document', date('Y-m-d')) }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm px-2 py-1">
            </div>

            {{-- Client --}}
            <div>
                <label for="client_code" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Client</label>
                <input list="clients_modal" name="client_code" id="client_code" ...>
                <datalist id="clients_modal">
                    @foreach ($clients as $client)
                        <option value="{{ $client->code_cli }}">{{ $client->societe }} ({{ $client->code_cli }})</option>
                    @endforeach
                </datalist>
            </div>

            {{-- Statut --}}
            <div>
                <label for="statut" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Statut</label>
                <select name="statut" id="statut"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm px-2 py-1">
                    <option value="brouillon">Brouillon</option>
                    <option value="envoye">Envoyé</option>
                    <option value="paye">Payé</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Lignes du document --}}
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg mt-4">
        <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-t-lg font-semibold text-gray-900 dark:text-gray-100">
            Lignes du document
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 dark:border-gray-700" id="lignesTable">
                <thead class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Produit</th>
                        <th class="border px-2 py-1">Description</th>
                        <th class="border px-2 py-1">Quantité</th>
                        <th class="border px-2 py-1">Prix HT (€)</th>
                        <th class="border px-2 py-1">TVA (%)</th>
                        <th class="border px-2 py-1">Total TTC (€)</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input list="produits" name="lignes[0][produit_code]" placeholder="Choisir un produit..."
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-2 py-1 produit-input">
                        </td>
                        <td>
                            <input type="text" name="lignes[0][description]" readonly
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 description">
                        </td>
                        <td>
                            <input type="number" name="lignes[0][quantite]" value="1" min="1"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-2 py-1 quantite">
                        </td>
                        <td>
                            <input type="number" name="lignes[0][prix_unitaire_ht]" step="0.01" readonly
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 prix">
                        </td>
                        <td>
                            <input type="number" name="lignes[0][taux_tva]" step="0.1" readonly
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 tva">
                        </td>
                        <td>
                            <input type="number" name="lignes[0][total_ttc]" step="0.01" readonly
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 total">
                        </td>
                        <td class="text-center flex justify-center gap-1">
                        <button type="button" class= "text-white rounded px-2 py-1 addRowBelow">➕</button>
                        <button type="button" class= "text-white rounded px-2 py-1 removeRow">🗑️</button>
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
        </div>
    </div>

    {{-- Totaux --}}
    <div class="text-right mt-4 space-y-1 text-gray-900 dark:text-gray-100">
        <div><strong>Total HT :</strong> <span id="display_total_ht">0.00 €</span></div>
        <div><strong>Total TVA :</strong> <span id="display_total_tva">0.00 €</span></div>
        <div><strong>Total TTC :</strong> <span id="display_total_ttc">0.00 €</span></div>
    </div>

    {{-- Boutons --}}
    <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <x-primary-button type="submit">Enregistrer</x-primary-button>
        <button type="button" onclick="closeModal()"
                class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-500">
             Retour
        </button>
    </div>
</form>
