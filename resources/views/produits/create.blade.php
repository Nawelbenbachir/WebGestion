<form action="{{ route('produits.store') }}" method="POST" class="text-sm">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-900 p-3 rounded-t-lg border-b dark:border-gray-700">

        {{-- COLONNE GAUCHE : INFOS PRODUIT --}}
        <div class="space-y-2 border-r dark:border-gray-700 pr-4">
            <h3 class="font-bold text-blue-600 dark:text-blue-400 uppercase text-xs">Informations Produit</h3>

            {{-- Code produit --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Code produit</label>
                <input type="text" name="code_produit" id="code_produit"
                       value="{{ old('code_produit') ?: 'PRD' . strtoupper(substr(old('description') ?? '', 0, 3)) . rand(100, 999) }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('code_produit')" />
            </div>

            {{-- Code comptable --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Code comptable</label>
                <input type="text" name="code_comptable" id="code_comptable"
                       value="{{ old('code_comptable') ?: 'CPT' . strtoupper(substr(old('nom') ?? '', 0, 1)) . now()->format('YmdHis') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('code_comptable')" />
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Description</label>
                <input type="text" name="description" id="description"
                       value="{{ old('description') }}"
                       required
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('description')" />
            </div>

            {{-- Prix HT + TVA --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Prix HT</label>
                    <input type="number" name="prix_ht" id="prix_ht" step="0.01"
                           value="{{ old('prix_ht') }}"
                           required
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                    <x-input-error :messages="$errors->get('prix_ht')" />
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">TVA (%)</label>
                    <select name="tva" id="tva" required
                            class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
                        <option value="">-- Sélectionner --</option>
                        <option value="20"  @selected(old('tva') == '20')>20%</option>
                        <option value="10"  @selected(old('tva') == '10')>10%</option>
                        <option value="5.5" @selected(old('tva') == '5.5')>5.5%</option>
                    </select>
                    <x-input-error :messages="$errors->get('tva')" />
                </div>
            </div>

            {{-- Stock --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Stock</label>
                <input type="number" name="qt_stock" id="qt_stock"
                       value="{{ old('qt_stock', 0) }}"
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
                <select name="categorie" id="categorie"
                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('categorie') == $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('categorie')" />
            </div>

            {{-- Nouvelle catégorie --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Nouvelle catégorie</label>
                <input type="text" name="nouvelle_categorie" id="nouvelle_categorie"
                       value="{{ old('nouvelle_categorie') }}"
                       placeholder="Ajouter une nouvelle catégorie"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('nouvelle_categorie')" />
            </div>
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="flex justify-end items-center gap-3 p-4 border-t bg-gray-50 dark:bg-gray-900 rounded-b-lg mt-2">
        <x-primary-button type="submit" class="px-6 py-2 shadow-md">
            Enregistrer
        </x-primary-button>
        <a href="{{ route('produits.index') }}"
           class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded shadow-sm text-sm font-medium hover:bg-gray-50 transition-colors">
            Retour
        </a>
    </div>
</form>