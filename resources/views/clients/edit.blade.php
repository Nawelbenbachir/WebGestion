<form id="client-edit-form" action="{{ route('clients.update', $client->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Colonne gauche --}}
        <div class="space-y-4">
            @foreach ([
                'code_cli' => 'Code client',
                'code_comptable' => 'Code comptable',
                'societe' => 'Société',
                'nom' => 'Nom',
                'prenom' => 'Prénom',
                'email' => 'Adresse e-mail',
                'telephone' => 'Téléphone'
            ] as $field => $label)
                <div>
                    <x-input-label for="{{ $field }}" :value="$label" />
                    <input
                        type="{{ $field === 'email' ? 'email' : 'text' }}"
                        name="{{ $field }}"
                        id="{{ $field }}"
                        value="{{ old($field, $client->$field ?? '') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <x-input-error :messages="$errors->get($field)" />
                </div>
            @endforeach

            {{-- Portables --}}
            <div class="grid grid-cols-2 gap-4">
                @foreach(['portable1'=>'Portable 1','portable2'=>'Portable 2'] as $field => $label)
                    <div>
                        <x-input-label for="{{ $field }}" :value="$label" />
                        <input
                            type="text"
                            name="{{ $field }}"
                            id="{{ $field }}"
                            value="{{ old($field, $client->$field ?? '') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm"
                        >
                        <x-input-error :messages="$errors->get($field)" />
                    </div>
                @endforeach
            </div>

            {{-- Type et Mode de règlement --}}
            <div>
                <x-input-label for="type" value="Type de client"/>
                <select
                    name="type"
                    id="type"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm"
                >
                    <option value="">-- Sélectionner un type --</option>
                    <option value="particulier" {{ old('type', $client->type) == 'particulier' ? 'selected' : '' }}>Particulier</option>
                    <option value="artisan" {{ old('type', $client->type) == 'artisan' ? 'selected' : '' }}>Artisan</option>
                    <option value="entreprise" {{ old('type', $client->type) == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                </select>
                <x-input-error :messages="$errors->get('type')" />
            </div>

            <div>
                <x-input-label for="reglement" value="Mode de règlement"/>
                <select
                    name="reglement"
                    id="reglement"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm"
                >
                    <option value="">-- Sélectionner un mode de règlement --</option>
                    <option value="virement" {{ ($client->reglement ?? '') === 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="cheque" {{ ($client->reglement ?? '') === 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="especes" {{ ($client->reglement ?? '') === 'especes' ? 'selected' : '' }}>Espèces</option>
                </select>
                <x-input-error :messages="$errors->get('reglement')" />
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="space-y-4">
            @foreach(['adresse1'=>'Adresse','adresse2'=>'Adresse 2','complement_adresse'=>"Complément d'adresse"] as $field => $label)
                <div>
                    <x-input-label for="{{ $field }}" :value="$label"/>
                    <textarea
                        name="{{ $field }}"
                        id="{{ $field }}"
                        rows="2"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm"
                    >{{ old($field, $client->$field ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get($field)" />
                </div>
            @endforeach

            <div class="grid grid-cols-2 gap-4">
                @foreach(['ville'=>'Ville','code_postal'=>'Code Postal'] as $field => $label)
                    <div>
                        <x-input-label for="{{ $field }}" :value="$label"/>
                        <input
                            type="text"
                            name="{{ $field }}"
                            id="{{ $field }}"
                            value="{{ old($field, $client->$field ?? '') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm"
                        >
                        <x-input-error :messages="$errors->get($field)" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Boutons --}}
    <div class="flex justify-end gap-3 mt-4">
        <x-secondary-button type="button" onclick="closeModal()">Annuler</x-secondary-button>
        <x-primary-button type="submit">Enregistrer</x-primary-button>
    </div>
</form>
