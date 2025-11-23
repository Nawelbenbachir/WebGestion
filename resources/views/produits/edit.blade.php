<form id="produit-edit-form" action="{{ route('produits.update', $produit->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Colonne gauche --}}
        <div class="space-y-4">
            @foreach ([
                'code_produit' => 'Code produit',
                'code_comptable' => 'Code comptable',
                'description' => 'Description'
            ] as $field => $label)
                <div>
                    <x-input-label for="{{ $field }}" :value="$label" />
                    <input 
                        type="text" 
                        name="{{ $field }}" 
                        id="{{ $field }}" 
                        value="{{ old($field, $produit->$field ?? '') }}"
                        required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <x-input-error :messages="$errors->get($field)" />
                </div>
            @endforeach

            {{-- Prix HT et TVA --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="prix_ht" value="Prix HT" />
                    <input 
                        type="number" 
                        name="prix_ht" 
                        id="prix_ht" 
                        step="0.01"
                        value="{{ old('prix_ht', $produit->prix_ht ?? '') }}"
                        required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <x-input-error :messages="$errors->get('prix_ht')" />
                </div>
                <div>
                    <x-input-label for="tva" value="TVA (%)" />
                    <input 
                        type="number" 
                        name="tva" 
                        id="tva" 
                        step="0.01"
                        value="{{ old('tva', $produit->tva ?? 20) }}"
                        required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <x-input-error :messages="$errors->get('tva')" />
                </div>
            </div>

            {{-- Stock --}}
            <div>
                <x-input-label for="qt_stock" value="Stock" />
                <input 
                    type="number" 
                    name="qt_stock" 
                    id="qt_stock" 
                    value="{{ old('qt_stock', $produit->qt_stock ?? 0) }}"
                    required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                <x-input-error :messages="$errors->get('qt_stock')" />
            </div>
        </div> {{-- Fin colonne gauche --}}

        {{-- Colonne droite --}}
        <div class="space-y-4">
            {{-- Sélecteur de catégories existantes --}}
    <div>
        <x-input-label for="categorie_select" value="Catégorie existante" />
        <select 
            name="categorie_select" 
            id="categorie_select" 
            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
            <option value="">-- Sélectionner une catégorie --</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie }}" {{ old('categorie_select', $produit->categorie) == $categorie ? 'selected' : '' }}>
                    {{ $categorie }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('categorie_select')" />
    </div>

    {{-- Champ pour nouvelle catégorie --}}
    <div>
        <x-input-label for="categorie" value="Nouvelle catégorie" />
        <input 
            type="text" 
            name="categorie" 
            id="categorie" 
            value="{{ old('categorie', $produit->categorie ?? '') }}"
            placeholder="Ajouter une nouvelle catégorie"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
        <x-input-error :messages="$errors->get('categorie')" />
    </div>
        </div> {{-- Fin colonne droite --}}

        

    </div> {{-- Fin grid --}}

    {{-- Boutons --}}
    <div class="flex justify-end gap-3 mt-4">
        <x-secondary-button as="a" href="{{ route('produits.index') }}">Annuler</x-secondary-button>
        <x-primary-button type="submit">Enregistrer</x-primary-button>
    </div>
</form>
