<form method="POST" action="{{ route('societes.update', $societe->id) }}">
    @csrf
    @method('PUT')

    {{-- Informations générales --}}
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Code société --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Code</label>
                <input type="text" name="code_societe"
                       value="{{ old('code_societe', $societe->code_societe) }}"
                       required class="form-input" >
            </div>

            {{-- Nom société --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Nom *</label>
                <input type="text" name="nom_societe"
                       value="{{ old('nom_societe', $societe->nom_societe) }}"
                       required class="form-input">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Email *</label>
                <input type="email" name="email"
                       value="{{ old('email', $societe->email) }}"
                       required class="form-input">
            </div>

            {{-- Téléphone --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Téléphone</label>
                <input type="text" name="telephone"
                       value="{{ old('telephone', $societe->telephone) }}"
                       class="form-input">
            </div>

           

            {{-- Siret --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">SIRET</label>
                <input type="text" name="siret"
                       value="{{ old('siret', $societe->siret) }}"
                       class="form-input">
            </div>

            {{-- Adresse 1 --}}
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Adresse</label>
                <input type="text" name="adresse1"
                       value="{{ old('adresse1', $societe->adresse1) }}"
                       class="form-input">
            </div>

            {{-- Adresse 2 --}}
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Adresse 2</label>
                <input type="text" name="adresse2"
                       value="{{ old('adresse2', $societe->adresse2) }}"
                       class="form-input">
            </div>

            {{-- Complément --}}
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Complément</label>
                <input type="text" name="complement_adresse"
                       value="{{ old('complement_adresse', $societe->complement_adresse) }}"
                       class="form-input">
            </div>

            {{-- Code Postal --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Code Postal</label>
                <input type="text" name="code_postal"
                       value="{{ old('code_postal', $societe->code_postal) }}"
                       class="form-input">
            </div>

            {{-- Ville --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Ville</label>
                <input type="text" name="ville"
                       value="{{ old('ville', $societe->ville) }}"
                       class="form-input">
            </div>

            {{-- IBAN --}}
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">IBAN</label>
                <input type="text" name="iban" maxlength="34"
                       value="{{ old('iban', $societe->iban) }}"
                       placeholder="FR76..."
                       class="form-input">
            </div>

            {{-- SWIFT --}}
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">SWIFT / BIC</label>
                <input type="text" name="swift" maxlength="11"
                       value="{{ old('swift', $societe->swift) }}"
                       placeholder="BNPAFRPP"
                       class="form-input">
            </div>

        </div>
    </div>

    {{-- Boutons --}}
    <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <x-primary-button type="submit">Mettre à jour</x-primary-button>

        <button type="button" onclick="closeModal()"
                class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-500">
             Retour
        </button>
    </div>

</form>
