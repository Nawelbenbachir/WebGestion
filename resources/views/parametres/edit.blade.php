<form id="societe-edit-form" action="{{ route('societes.update', $societe->id) }}" method="POST" class="text-sm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 bg-gray-50 dark:bg-gray-900 p-3 rounded-t-lg border-b dark:border-gray-700">
        
        {{-- COLONNE GAUCHE : IDENTITÉ (col-span 5) --}}
        <div class="md:col-span-5 space-y-3 border-r dark:border-gray-700 pr-4">
            <h3 class="font-bold text-blue-600 dark:text-blue-400 uppercase text-xs tracking-wider">Identité & Contact</h3>
            
            <div class="grid grid-cols-3 gap-2">
                <div class="col-span-1">
                    <label class="block text-xs font-medium dark:text-gray-400">Code</label>
                    <input type="text" name="code_societe" value="{{ old('code_societe', $societe->code_societe) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 font-mono uppercase" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium dark:text-gray-400">Nom de la Société *</label>
                    <input type="text" name="nom_societe" value="{{ old('nom_societe', $societe->nom_societe) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium dark:text-gray-400">SIRET</label>
                <input type="text" name="siret" value="{{ old('siret', $societe->siret) }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-400">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $societe->telephone) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-400">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $societe->email) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1" required>
                </div>
            </div>
        </div>

        {{-- COLONNE DROITE : LOCALISATION & BANQUE (col-span 7) --}}
        <div class="md:col-span-7 space-y-3">
            <h3 class="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-xs tracking-wider">Localisation & Paiement</h3>
            
            <div class="space-y-1">
                <label class="block text-xs font-medium dark:text-gray-400">Adresse</label>
                <input type="text" name="adresse1" value="{{ old('adresse1', $societe->adresse1) }}" placeholder="Rue..."
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <input type="text" name="adresse2" value="{{ old('adresse2', $societe->adresse2) }}" placeholder="Appartement, étage..."
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <input type="text" name="complement_adresse" value="{{ old('complement_adresse', $societe->complement_adresse) }}" placeholder="Complément (ZA, etc.)"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div class="col-span-1">
                    <label class="block text-xs font-medium dark:text-gray-400">Code Postal</label>
                    <input type="text" name="code_postal" value="{{ old('code_postal', $societe->code_postal) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium dark:text-gray-400">Ville</label>
                    <input type="text" name="ville" value="{{ old('ville', $societe->ville) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
            </div>

            <div class="grid grid-cols-12 gap-2 p-2 bg-blue-50/50 dark:bg-blue-900/10 rounded-lg border border-blue-100 dark:border-blue-900/30">
                <div class="col-span-8">
                    <label class="block text-xs font-bold text-blue-700 dark:text-blue-300">IBAN</label>
                    <input type="text" name="iban" value="{{ old('iban', $societe->iban) }}" maxlength="34" placeholder="FR76..."
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 font-mono text-xs uppercase">
                </div>
                <div class="col-span-4">
                    <label class="block text-xs font-bold text-blue-700 dark:text-blue-300">SWIFT / BIC</label>
                    <input type="text" name="swift" value="{{ old('swift', $societe->swift) }}" maxlength="11" placeholder="BNPAFRPP"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 font-mono text-xs uppercase">
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER : ACTIONS DÉCALÉES EN BAS À DROITE --}}
    <div class="flex justify-end items-center gap-3 p-4 border-t bg-gray-50 dark:bg-gray-900 rounded-b-lg mt-0">
        <button type="button" onclick="closeModal()" 
                class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded shadow-sm text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            Annuler
        </button>
        <x-primary-button class="px-6 py-2 shadow-md">
            Mettre à jour
        </x-primary-button>
    </div>
</form>