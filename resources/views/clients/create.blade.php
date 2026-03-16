<form id="client-form" action="{{ route('clients.store') }}" method="POST" class="text-sm">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-900 p-3 rounded-t-lg border-b dark:border-gray-700">

        {{-- COLONNE GAUCHE : IDENTITÉ --}}
        <div class="space-y-2 border-r dark:border-gray-700 pr-4">
            <h3 class="font-bold text-blue-600 dark:text-blue-400 uppercase text-xs">Informations Client</h3>

            {{-- Code client --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Code client</label>
                <input type="text" id="code_cli" name="code_cli"
                       value="{{ old('code_cli') ?: 'CLT' . strtoupper(substr(old('nom') ?? '', 0, 3)) . rand(100, 999) }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('code_cli')" />
            </div>

            {{-- Code comptable --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Code comptable</label>
                <input type="text" id="code_comptable" name="code_comptable"
                       value="{{ old('code_comptable') ?: 'CPT' . strtoupper(substr(old('nom') ?? '', 0, 1)) . now()->format('YmdHis') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('code_comptable')" />
            </div>

            {{-- Société --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Société</label>
                <input type="text" id="societe" name="societe"
                       value="{{ old('societe') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('societe')" />
            </div>

            {{-- Nom + Prénom --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Nom</label>
                    <input type="text" id="nom" name="nom"
                           value="{{ old('nom') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                    <x-input-error :messages="$errors->get('nom')" />
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Prénom</label>
                    <input type="text" id="prenom" name="prenom"
                           value="{{ old('prenom') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                    <x-input-error :messages="$errors->get('prenom')" />
                </div>
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Type de client</label>
                <select id="type" name="type"
                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
                    <option value="">-- Sélectionner --</option>
                    <option value="particulier" @selected(old('type')=='particulier')>Particulier</option>
                    <option value="artisan"     @selected(old('type')=='artisan')>Artisan</option>
                    <option value="entreprise"  @selected(old('type')=='entreprise')>Entreprise</option>
                </select>
                <x-input-error :messages="$errors->get('type')" />
            </div>

            {{-- Numéro de TVA --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Numéro de TVA</label>
                <input type="text" id="tva" name="tva"
                       value="{{ old('tva') }}"
                       placeholder="FR00000000000"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('tva')" />
            </div>

            {{-- Mode de règlement --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Mode de règlement</label>
                <select id="reglement" name="reglement"
                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
                    <option value="">-- Sélectionner --</option>
                    <option value="virement" @selected(old('reglement')=='virement')>Virement</option>
                    <option value="cheque"   @selected(old('reglement')=='cheque')>Chèque</option>
                    <option value="especes"  @selected(old('reglement')=='especes')>Espèces</option>
                </select>
                <x-input-error :messages="$errors->get('reglement')" />
            </div>
        </div>

        {{-- COLONNE DROITE : CONTACT & ADRESSE --}}
        <div class="space-y-2">
            <h3 class="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-xs">Contact & Adresse</h3>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Adresse e-mail</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('email')" />
            </div>

            {{-- Téléphone + Portable 1 --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Téléphone</label>
                    <input type="text" id="telephone" name="telephone"
                           value="{{ old('telephone') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Portable 1</label>
                    <input type="text" id="portable1" name="portable1"
                           value="{{ old('portable1') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
            </div>

            {{-- Portable 2 --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Portable 2</label>
                <input type="text" id="portable2" name="portable2"
                       value="{{ old('portable2') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
            </div>

            {{-- Adresses --}}
            @foreach(['adresse1' => 'Adresse', 'adresse2' => 'Adresse 2', 'complement_adresse' => "Complément d'adresse"] as $field => $label)
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">{{ $label }}</label>
                    <textarea name="{{ $field }}" id="{{ $field }}" rows="2"
                              class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">{{ old($field) }}</textarea>
                    <x-input-error :messages="$errors->get($field)" />
                </div>
            @endforeach

            {{-- CP + Ville --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Code Postal</label>
                    <input type="text" id="code_postal" name="code_postal"
                           value="{{ old('code_postal') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Ville</label>
                    <input type="text" id="ville" name="ville"
                           value="{{ old('ville') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="flex justify-end items-center gap-3 p-4 border-t bg-gray-50 dark:bg-gray-900 rounded-b-lg mt-2">
        <x-primary-button type="submit" class="px-6 py-2 shadow-md">
            Enregistrer
        </x-primary-button>
        <a href="{{ route('clients.index') }}"
           class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded shadow-sm text-sm font-medium hover:bg-gray-50 transition-colors">
            Retour
        </a>
    </div>
</form>