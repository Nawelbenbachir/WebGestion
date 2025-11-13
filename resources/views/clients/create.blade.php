<x-layouts.app>
    <div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">➕ Ajouter un client</h1>

        <form id="client-form" action="{{ route('clients.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Colonne gauche --}}
                <div class="space-y-4">
                    <x-input-group label="Code client" name="code_cli"
                        :value="old('code_cli') ?: 'CLT' . strtoupper(substr(old('nom') ?? '', 0, 3)) . rand(100, 999)" />

                    <x-input-group label="Code comptable" name="code_comptable"
                        :value="old('code_comptable') ?: 'CPT' . strtoupper(substr(old('nom') ?? '', 0, 1)) . now()->format('YmdHis')" />

                    <x-input-group label="Société" name="societe" :value="old('societe')" required />

                    <x-input-group label="Nom" name="nom" :value="old('nom')" required />

                    <x-input-group label="Prénom" name="prenom" :value="old('prenom')" />

                    <x-input-group type="email" label="Adresse e-mail" name="email" :value="old('email')" />

                    <x-input-group label="Téléphone" name="telephone" :value="old('telephone')" />

                    <x-input-group label="Portable 1" name="portable1" :value="old('portable1')" />

                    <x-input-group label="Portable 2" name="portable2" :value="old('portable2')" />

                    <x-select-group label="Type de client" name="type" required>
                        <option value="">-- Sélectionner un type --</option>
                        <option value="particulier" @selected(old('type') == 'particulier')>Particulier</option>
                        <option value="artisan" @selected(old('type') == 'artisan')>Artisan</option>
                        <option value="entreprise" @selected(old('type') == 'entreprise')>Entreprise</option>
                    </x-select-group>

                    <x-select-group label="Mode de règlement" name="reglement">
                        <option value="">-- Sélectionner un mode de règlement --</option>
                        <option value="virement" @selected(old('reglement') == 'virement')>Virement</option>
                        <option value="cheque" @selected(old('reglement') == 'cheque')>Chèque</option>
                        <option value="especes" @selected(old('reglement') == 'especes')>Espèces</option>
                    </x-select-group>
                </div>

                {{-- Colonne droite --}}
                <div class="space-y-4">
                    <x-textarea-group label="Adresse" name="adresse1" rows="2">{{ old('adresse1') }}</x-textarea-group>

                    <x-textarea-group label="Adresse 2" name="adresse2" rows="2">{{ old('adresse2') }}</x-textarea-group>

                    <x-textarea-group label="Complément d'adresse" name="complement_adresse" rows="2">{{ old('complement_adresse') }}</x-textarea-group>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input-group label="Ville" name="ville" :value="old('ville')" />
                        <x-input-group label="Code postal" name="code_postal" :value="old('code_postal')" />
                    </div>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-secondary-button as="a" href="{{ route('clients.index') }}"> Retour</x-secondary-button>
                <x-primary-button type="submit"> Enregistrer le client</x-primary-button>
            </div>
        </form>
    </div>
</x-layouts.app>
