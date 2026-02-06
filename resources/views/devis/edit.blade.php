
<form id="devis-edit-form" action="{{ route('documents.update', $document->id) }}" method="POST" class="text-sm">
    @csrf
    @method('PUT')

    {{-- Hidden Fields --}}
    <input type="hidden" name="societe_id" value="{{ $document->societe_id }}">
    <input type="hidden" id="total_ht" name="total_ht" value="{{ $document->total_ht }}">
    <input type="hidden" id="total_tva" name="total_tva" value="{{ $document->total_tva }}">
    <input type="hidden" id="total_ttc" name="total_ttc" value="{{ $document->total_ttc }}">
    <input type="hidden" id="type_document" name="type_document" value="{{ $type ?? 'devis' }}">

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 bg-gray-50 dark:bg-gray-900 p-3 rounded-t-lg border-b dark:border-gray-700">
        
        {{-- COLONNE GAUCHE : CLIENT (col-span 5) --}}
        <div class="md:col-span-5 space-y-2 border-r dark:border-gray-700 pr-4">
            <h3 class="font-bold text-blue-600 dark:text-blue-400 uppercase text-xs">Informations Client</h3>
            
            <div>
               <select name="client_id" id="client_id" required
                    class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <option value="">-- Choisir un client --</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" 
                            @selected($client->id == $document->client_id)
                            data-adresse1="{{ $client->adresse1 }}"
                            data-adresse2="{{ $client->adresse2 }}"
                            data-cp="{{ $client->code_postal }}"
                            data-ville="{{ $client->ville }}"
                            data-tel="{{ $client->telephone }}"
                            data-email="{{ $client->email }}">
                        {{ $client->code_cli }} - {{ $client->societe }} 
                    </option>
                @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Adresse</label>
                <input type="text" name="adresse1" id="adresse1" value="{{ old('adresse1', $document->client->adresse1) }}" placeholder="Rue..."
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 mb-1" readonly>
                <input type="text" name="adresse2" id="adresse2" value="{{ old('adresse2', $document->client->adresse2) }}" placeholder="Appt, étage..."
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1" readonly >
            </div>

            <div class="grid grid-cols-3 gap-1">
                <div class="col-span-1">
                    <label class="block text-xs font-medium">CP</label>
                    <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal', $document->client->code_postal) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1"  readonly>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium">Ville</label>
                    <input type="text" name="ville" id="ville" value="{{ old('ville', $document->client->ville) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1"  readonly>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $document->telephone) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 px-2 py-1" readonly>
                </div>
                <div>
                    <label class="block text-xs font-medium">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $document->email) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 px-2 py-1">
                </div>
            </div>
        </div>

        {{-- COLONNE DROITE : DOCUMENT (col-span 7) --}}
        <div class="md:col-span-7 space-y-2">
            <h3 class="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-xs">Détails du Document</h3>
            
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium">N° de Document</label>
                    <input type="text" value="{{ $document->code_document }}" readonly
                           class="w-full rounded border-gray-200 bg-gray-100 dark:bg-gray-700 px-2 py-1 font-mono"  readonly>
                </div>
                <div>
                    <label class="block text-xs font-medium">Statut</label>
                    <select name="statut" class="w-full rounded border-gray-300 dark:bg-gray-800 px-2 py-1 text-sm">
                        <option value="brouillon" @selected($document->statut=='brouillon')>Brouillon</option>
                        <option value="envoye" @selected($document->statut=='envoye')>Envoyé</option>
                        <option value="paye" @selected($document->statut=='paye')>Payé</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium">Date Document</label>
                    <input type="date" name="date_document" value="{{ old('date_document', $document->date_document) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-medium">Échéance / Validité</label>
                    <input type="date" name="date_echeance" value="{{ old('date_validite', $document->date_validité) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 px-2 py-1">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium">Commentaire interne / Note</label>
                <textarea name="commentaire" rows="2"
                          class="w-full rounded border-gray-300 dark:bg-gray-800 px-2 py-1">{{ old('commentaire', $document->commentaire) }}</textarea>
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
                    @foreach($document->lignes as $i => $ligne)
                        <tr>
                             <td>
                                <input list="produits" 
                                name="lignes[{{ $i }}][produit_code]" 
                                placeholder="Choisir un produit..."
                                class="produit-input"
                                value="{{ old("lignes.$i.produit_code", $ligne->produit->code_produit ?? '') }}"
                                >
                            </td>
                            <td>
                                <input type="text" name="lignes[{{ $i }}][description]" readonly
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 description"
                                       value="{{ old("lignes.$i.description", $ligne->description) }}">
                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][quantite]" value="{{ old("lignes.$i.quantite", $ligne->quantite) }}" min="1"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-2 py-1 quantite">
                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][prix_unitaire_ht]" step="0.01" readonly
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 prix"
                                       value="{{ old("lignes.$i.prix_unitaire_ht", $ligne->prix_unitaire_ht) }}"

                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][taux_tva]" step="0.1" readonly
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 tva"
                                       value="{{ old("lignes.$i.taux_tva", $ligne->taux_tva) }}">
                            </td>
                            <td>
                                <input type="number" name="lignes[{{ $i }}][total_ttc]" step="0.01" readonly
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 total"
                                       value="{{ old("lignes.$i.total_ttc", $ligne->total_ttc) }}">
                            </td>
                            <td class="text-center flex justify-center gap-1">
                                {{-- Bouton Ajouter --}}
                                <button type="button" 
                                        class="addRowBelow p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-md transition-all group"
                                        title="Ajouter en dessous">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                                
                                {{-- Bouton Supprimer --}}
                                <button type="button" 
                                        class="removeRow p-1.5 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-md transition-all group"
                                        title="Supprimer la ligne">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            

            <datalist id="produits">
                @foreach ($produits as $produit)
                    <option value="{{ $produit->code_produit }}"
                            data-id="{{ $produit->id }}"
                            data-prix="{{ $produit->prix_ht }}"
                            data-tva="{{ $produit->tva }}"
                            data-designation="{{ $produit->description }}">
                        {{ $produit->description }} ({{ $produit->code_produit }})
                    </option>
                @endforeach
            </datalist>
        </div>
    </div>

  

  {{-- FOOTER ALIGNÉ --}}
                <tfoot class="bg-gray-50 dark:bg-gray-800 font-bold">
                    <tr>
                        {{-- On fusionne les 3 premières colonnes --}}
                        <td colspan="3" class="text-right px-4 py-2 uppercase text-xs text-gray-500">Totaux du document</td>
                        
                        {{-- Alignement sous Prix HT --}}
                        <td class="border px-2 py-2 text-right">
                            <span id="display_total_ht">{{ number_format($document->total_ht,2) }}</span> €
                        </td>
                        
                        {{-- Alignement sous TVA --}}
                        <td class="border px-2 py-2 text-right">
                            <span id="display_total_tva">{{ number_format($document->total_tva,2) }}</span> €
                        </td>
                        
                        {{-- Alignement sous Total TTC --}}
                        <td class="border px-2 py-2 text-right bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                            <span id="display_total_ttc">{{ number_format($document->total_ttc,2) }}</span> €
                        </td>
                        
                        {{-- Colonne vide sous les boutons d'action --}}
                        <td class="bg-gray-100 dark:bg-gray-800/50"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </table>

    {{-- ACTIONS DÉCALÉES EN BAS À DROITE --}}
    <div class="flex justify-end items-center gap-3 p-4 border-t bg-gray-50 dark:bg-gray-900 rounded-b-lg mt-2">
        <x-primary-button class="px-6 py-2 shadow-md">
            Enregistrer
        </x-primary-button>
        <button type="button" onclick="closeModal()" 
                class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded shadow-sm text-sm font-medium hover:bg-gray-50 transition-colors">
            Annuler
        </button>
        
    </div>
</form>
