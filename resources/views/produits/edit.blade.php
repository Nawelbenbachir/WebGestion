<form id="produit-form" action="{{ route('produits.update', $produit->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <!-- Grid à 2 colonnes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Code produit -->
        <div>
            <label for="code_produit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code</label>
            <input type="text" name="code_produit" id="code_produit"
                   value="{{ old('code_produit', $produit->code_produit) }}"
                   required
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 
                          text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Code comptable -->
        <div>
            <label for="code_comptable" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code comptable</label>
            <input type="text" name="code_comptable" id="code_comptable"
                   value="{{ old('code_comptable', $produit->code_comptable) }}"
                   required
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 
                          text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Description -->
        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
            <input type="text" name="description" id="description"
                   value="{{ old('description', $produit->description) }}"
                   required
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 
                          text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Prix HT -->
        <div>
            <label for="prix_ht" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix HT</label>
            <input type="number" name="prix_ht" id="prix_ht" step="0.01"
                   value="{{ old('prix_ht', $produit->prix_ht) }}"
                   required
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 
                          text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('prix_ht')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- TVA -->
        <div>
            <label for="tva" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">TVA (%)</label>
            <input type="number" name="tva" id="tva" step="0.01"
                   value="{{ old('tva', $produit->tva ?? 20) }}"
                   required
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 
                          text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('tva')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Stock -->
        <div>
            <label for="qt_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock</label>
            <input type="number" name="qt_stock" id="qt_stock"
                   value="{{ old('qt_stock', $produit->qt_stock ?? 0) }}"
                   required
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 
                          text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('qt_stock')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Catégorie -->
        <div>
            <label for="categorie" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catégorie</label>
            <input type="text" name="categorie" id="categorie"
                   value="{{ old('categorie', $produit->categorie) }}"
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 
                          text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('categorie')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Ces boutons ne s’afficheront que si on ouvre cette page directement (pas en modal) -->
    <div class="flex justify-end gap-3 pt-4">
        <x-secondary-button as="a" href="{{ route('produits.index') }}"> Retour</x-secondary-button>
        <x-primary-button type="submit"> Enregistrer</x-primary-button>
    </div>
</form>
