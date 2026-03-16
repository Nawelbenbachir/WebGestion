<form id="produit-edit-form" action="{{ route('produits.update', $produit->id) }}" method="POST" class="text-sm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-900 p-3 rounded-t-lg border-b dark:border-gray-700">

        {{-- COLONNE GAUCHE : INFOS PRODUIT --}}
        <div class="space-y-2 border-r dark:border-gray-700 pr-4">
            <h3 class="font-bold text-blue-600 dark:text-blue-400 uppercase text-xs">Informations Produit</h3>

            @foreach ([
                'code_produit' => 'Code produit',
                'code_comptable' => 'Code comptable',
                'description' => 'Description',
            ] as $field => $label)
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">{{ $label }}</label>
                    <input type="text" name="{{ $field }}" id="{{ $field }}"
                           value="{{ old($field, $produit->$field ?? '') }}"
                           required
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                    <x-input-error :messages="$errors->get($field)" />
                </div>
            @endforeach

            {{-- Prix HT + TVA --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Prix HT</label>
                    <input type="number" name="prix_ht" id="prix_ht" step="0.01"
                           value="{{ old('prix_ht', $produit->prix_ht ?? '') }}"
                           required
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                    <x-input-error :messages="$errors->get('prix_ht')" />
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">TVA (%)</label>
                    <input type="number" name="tva" id="tva" step="0.01"
                           value="{{ old('tva', $produit->tva ?? 20) }}"
                           required
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                    <x-input-error :messages="$errors->get('tva')" />
                </div>
            </div>

            {{-- Stock --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Stock</label>
                <input type="number" name="qt_stock" id="qt_stock"
                       value="{{ old('qt_stock', $produit->qt_stock ?? 0) }}"
                       required
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('qt_stock')" />
            </div>
        </div>

        {{-- COLONNE DROITE : CATÉGORIE --}}
        <div class="space-y-2">
            <h3 class="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-xs">Catégorie</h3>

            {{-- Catégorie existante --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Catégorie existante</label>
                <select name="categorie_select" id="categorie_select"
                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie }}" {{ old('categorie_select', $produit->categorie) == $categorie ? 'selected' : '' }}>
                            {{ $categorie }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('categorie_select')" />
            </div>

            {{-- Nouvelle catégorie --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Nouvelle catégorie</label>
                <input type="text" name="categorie" id="categorie"
                       value="{{ old('categorie', $produit->categorie ?? '') }}"
                       placeholder="Ajouter une nouvelle catégorie"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('categorie')" />
            </div>
        </div>
    </div>

    {{-- ACTIONS --}}
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