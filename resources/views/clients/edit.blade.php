<form id="client-edit-form" action="{{ route('clients.update', $client->id) }}" method="POST" class="text-sm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-900 p-3 rounded-t-lg border-b dark:border-gray-700">

        {{-- COLONNE GAUCHE : IDENTITÉ --}}
        <div class="space-y-2 border-r dark:border-gray-700 pr-4">
            <h3 class="font-bold text-blue-600 dark:text-blue-400 uppercase text-xs">Informations Client</h3>

            @foreach ([
                'code_cli' => 'Code client',
                'code_comptable' => 'Code comptable',
                'societe' => 'Société',
                'nom' => 'Nom',
                'prenom' => 'Prénom',
            ] as $field => $label)
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">{{ $label }}</label>
                    <input type="text" name="{{ $field }}" id="{{ $field }}"
                           value="{{ old($field, $client->$field ?? '') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                    <x-input-error :messages="$errors->get($field)" />
                </div>
            @endforeach

            {{-- Type de client --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Type de client</label>
                <select name="type" id="type"
                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
                    <option value="">-- Sélectionner un type --</option>
                    <option value="particulier" {{ old('type', $client->type) == 'particulier' ? 'selected' : '' }}>Particulier</option>
                    <option value="artisan"     {{ old('type', $client->type) == 'artisan'     ? 'selected' : '' }}>Artisan</option>
                    <option value="entreprise"  {{ old('type', $client->type) == 'entreprise'  ? 'selected' : '' }}>Entreprise</option>
                </select>
                <x-input-error :messages="$errors->get('type')" />
            </div>

            {{-- Numéro de TVA --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Numéro de TVA</label>
                <input type="text" name="tva" id="tva"
                       value="{{ old('tva', $client->tva ?? '') }}"
                       placeholder="FR00000000000"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('tva')" />
            </div>
        </div>

        {{-- COLONNE DROITE : CONTACT & ADRESSE --}}
        <div class="space-y-2">
            <h3 class="font-bold text-emerald-600 dark:text-emerald-400 uppercase text-xs">Contact & Adresse</h3>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Adresse e-mail</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $client->email ?? '') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                <x-input-error :messages="$errors->get('email')" />
            </div>

            {{-- Téléphone + portable --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Téléphone</label>
                    <input type="text" name="telephone" id="telephone"
                           value="{{ old('telephone', $client->telephone ?? '') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Portable 1</label>
                    <input type="text" name="portable1" id="portable1"
                           value="{{ old('portable1', $client->portable1 ?? '') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Portable 2</label>
                <input type="text" name="portable2" id="portable2"
                       value="{{ old('portable2', $client->portable2 ?? '') }}"
                       class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
            </div>

            {{-- Adresses --}}
            @foreach(['adresse1' => 'Adresse', 'adresse2' => 'Adresse 2', 'complement_adresse' => "Complément d'adresse"] as $field => $label)
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">{{ $label }}</label>
                    <textarea name="{{ $field }}" id="{{ $field }}" rows="2"
                              class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">{{ old($field, $client->$field ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get($field)" />
                </div>
            @endforeach

            {{-- CP + Ville --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Code Postal</label>
                    <input type="text" name="code_postal" id="code_postal"
                           value="{{ old('code_postal', $client->code_postal ?? '') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
                <div>
                    <label class="block text-xs font-medium dark:text-gray-300">Ville</label>
                    <input type="text" name="ville" id="ville"
                           value="{{ old('ville', $client->ville ?? '') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1">
                </div>
            </div>

            {{-- Mode de règlement --}}
            <div>
                <label class="block text-xs font-medium dark:text-gray-300">Mode de règlement</label>
                <select name="reglement" id="reglement"
                        class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
                    <option value="">-- Sélectionner --</option>
                    <option value="virement" {{ ($client->reglement ?? '') === 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="cheque"   {{ ($client->reglement ?? '') === 'cheque'   ? 'selected' : '' }}>Chèque</option>
                    <option value="especes"  {{ ($client->reglement ?? '') === 'especes'  ? 'selected' : '' }}>Espèces</option>
                </select>
                <x-input-error :messages="$errors->get('reglement')" />
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