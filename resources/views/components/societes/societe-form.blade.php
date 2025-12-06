@props(['isVueseule' => false])

<form action="{{ route('societes.store') }}" method="POST" class="p-6">
    @csrf

    {{-- Conteneur de la grille : 2 colonnes sur grand écran avec espacement --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
    
        <div>
            <label for="code_societe" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code société *</label>
            <input type="text" name="code_societe" 
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1" 
                   value="{{ old('code_societe') }}" required>
            
        </div>

        <div>
            <label for="nom_societe" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom *</label>
            <input type="text" name="nom_societe" 
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1" 
                   value="{{ old('nom_societe') }}" required>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
            <input type="email" name="email" 
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1" 
                   value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="ville" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</label>
            <input type="text" name="ville" 
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1" 
                   value="{{ old('ville') }}">
        </div>

        <div>
            <label for="pays" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pays</label>
            <input type="text" name="pays" 
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1" 
                   value="{{ old('pays', 'France') }}">
        </div>
        
        
        <div>
            <label for="swift" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SWIFT / BIC</label>
            <input type="text" name="swift" id="swift"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1 @error('swift') border-red-500 @enderror"
                value="{{ old('swift') }}" maxlength="11" placeholder="BNPAFRPP" >
            @error('swift') <div class="text-sm text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        
        <div class="md:col-span-2">
            <label for="iban" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IBAN</label>
            <input type="text" name="iban" id="iban"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1 @error('iban') border-red-500 @enderror"
                value="{{ old('iban') }}" maxlength="34" placeholder="FR76..." >
            @error('iban') <div class="text-sm text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>
        
    </div> 

    

    
    <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
        
        {{-- Logique du bouton Annuler --}}
        @if ($isVueseule)
             {{-- Page complète : lien de retour --}}
             <a href="{{ route('societes.index') }}" 
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-150">Annuler</a>
        @else
             {{-- Modale : appel à la fonction JS closeModal() --}}
             <button type="button" onclick="closeModal()" 
                     class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-150">Annuler</button>
        @endif
        
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-150">Enregistrer</button>
    </div>
</form>
