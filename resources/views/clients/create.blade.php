<form id="client-form" action="{{ route('clients.store') }}" method="POST" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Colonne gauche --}}
        <div class="space-y-4">
            
            {{-- Code client --}}
            <div>
                <x-input-label for="code_cli" value="Code client" />
                <input type="text" id="code_cli" name="code_cli"
                       value="{{ old('code_cli') ?: 'CLT' . strtoupper(substr(old('nom') ?? '', 0, 3)) . rand(100, 999) }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800
                              text-gray-900 dark:text-gray-100 shadow-sm">
                <x-input-error :messages="$errors->get('code_cli')" />
            </div>

            {{-- Code comptable --}}
            <div>
                <x-input-label for="code_comptable" value="Code comptable" />
                <input type="text" id="code_comptable" name="code_comptable"
                       value="{{ old('code_comptable') ?: 'CPT' . strtoupper(substr(old('nom') ?? '', 0, 1)) . now()->format('YmdHis') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm">
                <x-input-error :messages="$errors->get('code_comptable')" />
            </div>

            {{-- Société --}}
            <div>
                <x-input-label for="societe" value="Société" />
                <input type="text" id="societe" name="societe"
                       value="{{ old('societe') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                <x-input-error :messages="$errors->get('societe')" />
            </div>

            {{-- Nom --}}
            <div>
                <x-input-label for="nom" value="Nom" />
                <input type="text" id="nom" name="nom"
                       value="{{ old('nom') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                <x-input-error :messages="$errors->get('nom')" />
            </div>

            {{-- Prénom --}}
            <div>
                <x-input-label for="prenom" value="Prénom" />
                <input type="text" id="prenom" name="prenom"
                       value="{{ old('prenom') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                <x-input-error :messages="$errors->get('prenom')" />
            </div>

            {{-- Email --}}
            <div>
                <x-input-label for="email" value="Adresse e-mail" />
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                <x-input-error :messages="$errors->get('email')" />
            </div>

            {{-- Téléphone --}}
            <div>
                <x-input-label for="telephone" value="Téléphone" />
                <input type="text" id="telephone" name="telephone"
                       value="{{ old('telephone') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                <x-input-error :messages="$errors->get('telephone')" />
            </div>

            {{-- Portables --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="portable1" value="Portable 1" />
                    <input type="text" id="portable1" name="portable1" value="{{ old('portable1') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                </div>
                <div>
                    <x-input-label for="portable2" value="Portable 2" />
                    <input type="text" id="portable2" name="portable2" value="{{ old('portable2') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                </div>
            </div>

            {{-- Type --}}
            <div>
                <x-input-label for="type" value="Type de client"/>
                <select id="type" name="type"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                    <option value="">-- Sélectionner --</option>
                    <option value="particulier" @selected(old('type')=='particulier')>Particulier</option>
                    <option value="artisan" @selected(old('type')=='artisan')>Artisan</option>
                    <option value="entreprise" @selected(old('type')=='entreprise')>Entreprise</option>
                </select>
                <x-input-error :messages="$errors->get('type')" />
            </div>

            {{-- Règlement --}}
            <div>
                <x-input-label for="reglement" value="Mode de règlement"/>
                <select id="reglement" name="reglement"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                    <option value="">-- Sélectionner --</option>
                    <option value="virement" @selected(old('reglement')=='virement')>Virement</option>
                    <option value="cheque" @selected(old('reglement')=='cheque')>Chèque</option>
                    <option value="especes" @selected(old('reglement')=='especes')>Espèces</option>
                </select>
                <x-input-error :messages="$errors->get('reglement')" />
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="space-y-4">

            {{-- Adresse 1 --}}
            <div>
                <x-input-label for="adresse1" value="Adresse"/>
                <textarea id="adresse1" name="adresse1" rows="2"
                          class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">{{ old('adresse1') }}</textarea>
                <x-input-error :messages="$errors->get('adresse1')" />
            </div>

            {{-- Adresse 2 --}}
            <div>
                <x-input-label for="adresse2" value="Adresse 2"/>
                <textarea id="adresse2" name="adresse2" rows="2"
                          class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">{{ old('adresse2') }}</textarea>
                <x-input-error :messages="$errors->get('adresse2')" />
            </div>

            {{-- Complément --}}
            <div>
                <x-input-label for="complement_adresse" value="Complément d'adresse"/>
                <textarea id="complement_adresse" name="complement_adresse" rows="2"
                          class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">{{ old('complement_adresse') }}</textarea>
                <x-input-error :messages="$errors->get('complement_adresse')" />
            </div>

            {{-- Ville + CP --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="ville" value="Ville"/>
                    <input type="text" id="ville" name="ville" value="{{ old('ville') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                </div>

                <div>
                    <x-input-label for="code_postal" value="Code Postal"/>
                    <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm">
                </div>
            </div>

        </div>
    </div>

    <div class="flex justify-end gap-3 mt-4">
        <x-secondary-button type="button" onclick="closeCreateModal()">Annuler</x-secondary-button>
        <x-primary-button type="submit">Enregistrer</x-primary-button>
    </div>
</form>
