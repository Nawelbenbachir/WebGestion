<form id="reglements-create-form" action="{{ route('reglements.store') }}" method="POST" class="text-sm"
      x-data="{ 
        selectedClient: '',
        selectedDocs: [],
        search: '',
        allIds: {{ $documents->pluck('id') }}, {{-- On récupère tous les IDs via PHP --}}
    
        toggleAll(isChecked) {
            this.selectedDocs = isChecked ? [...this.allIds] : [];
        },
        get totalSelection() {
            let total = 0;
            this.selectedDocs.forEach(val => {
                // On force la recherche sur l'attribut value de manière plus robuste
                const el = document.querySelector(`input[name='document_ids[]'][value='${val}']`);
                
                if (el && el.dataset.solde) {
                    // On remplace la virgule par un point au cas où
                    let solde = el.dataset.solde.replace(',', '.');
                    let montant = parseFloat(solde);
                    
                    if (!isNaN(montant)) {
                        total += montant;
                    }
                }
            });
            return total.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
      }">
    @csrf

    <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-t-lg border-b dark:border-gray-700 space-y-4">
        <h3 class="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-xs">Informations du règlement</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium mb-1">Client</label>
                <select name="client_id" 
                    x-model="selectedClient" 
                    @change="selectedDocs = []" id="client_id" required
                    class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-3 py-2">
                    <option value="">-- Choisir un client --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->code_cli }} - {{ $client->societe }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium mb-1">N° de Règlement</label>
                <input type="text" value="{{ $prochainNumero }}" readonly
                       class="w-full rounded border-gray-200 bg-gray-100 dark:bg-gray-700 px-3 py-2 font-mono text-gray-500">
            </div>

            <div>
                <label class="block text-xs font-medium mb-1">Date Règlement</label>
                <input type="date" name="date_reglement" value="{{ date('Y-m-d') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium mb-1">Commentaire interne / Note</label>
            <textarea name="commentaire" rows="2" placeholder="Note pour ce règlement..."
                      class="w-full rounded border-gray-300 dark:bg-gray-800 px-3 py-2">{{ old('commentaire') }}</textarea>
        </div>
        <div x-show="selectedClient !== ''" x-transition>
                <label class="block text-xs font-medium mb-1 uppercase text-gray-500">Filtrer par référence</label>
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Rechercher un n° de facture..."
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white pl-10 pr-3 py-2 focus:ring-indigo-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>
    </div>

    <div class="p-0">
        @if($documents->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <p>Aucune facture en attente de paiement.</p>
            </div>
        @else
            <table class="min-w-full border-separate border-spacing-0" x-show="selectedClient !== ''" x-cloak>
                <thead>
                        <th class="px-6 py-3 border-b text-center w-10">
                            <input type="checkbox" @click="selectedDocs = $event.target.checked ? {{ $documents->pluck('id') }} : []" 
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-3 border-b text-left text-xs font-bold text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-3 border-b text-center text-xs font-bold text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 border-b text-right text-xs font-bold text-gray-500 uppercase">Total TTC</th>
                        <th class="px-6 py-3 border-b text-right text-xs font-bold text-gray-500 uppercase text-emerald-600">Solde dû</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($documents as $document)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all" x-show="selectedClient == '{{ $document->client_id }}' && '{{ $document->code_document }}'.toLowerCase().includes(search.toLowerCase())">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" 
                                       name="document_ids[]" 
                                       value="{{ $document->id }}" 
                                       x-model="selectedDocs"
                                       class="document-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       data-solde="{{ $document->solde }}">
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">{{ $document->code_document }}</td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $document->date_document }}</td>
                            <td class="px-6 py-4 text-right">{{ number_format($document->total_ttc, 2, ',', ' ') }} €</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                {{ number_format($document->solde, 2, ',', ' ') }} €
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div x-show="selectedClient === ''" class="py-12 text-center text-gray-500 italic">
            Veuillez sélectionner un client pour voir ses factures en attente.
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-b-lg border-t dark:border-gray-700 flex justify-between items-center">
        <div class="text-gray-600 dark:text-gray-400">
            Nombre de factures sélectionnées : <span class="font-bold" x-text="selectedDocs.length"></span>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">Total à régler</div>
            <div class="text-3xl font-black text-indigo-700 dark:text-indigo-400">
                <span x-text="totalSelection"></span> €
            </div>
        
            <button type="submit" 
            class=" mt-5 inline-flex items-center px-8 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                Enregistrer le règlement
            </button>
        </div>
    </div>
</form>