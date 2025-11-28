<div id="create-form-container" class="p-4 sm:p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
    
    <h3 class="text-xl font-bold mb-6 border-b pb-2 dark:border-gray-700">
        Création d'un nouvel utilisateur
    </h3>

    {{-- L'action pointe vers la route store, méthode POST --}}
    <form action="{{ route('user.store') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- Champs d'information de base --}}
        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                          :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                          :value="old('email')" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- Champ Rôle (par défaut sur 'user') --}}
        <div>
            <x-input-label for="role" :value="__('Rôle')" />
            <select id="role" name="role" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100">
                <option value="user" @selected(old('role') == 'user')>Utilisateur</option>
                <option value="manager" @selected(old('role') == 'manager')>Manager</option>
                <option value="admin" @selected(old('role') == 'admin')>Administrateur</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('role')" />
        </div>

        <h4 class="text-lg font-semibold pt-4 mt-4 border-t dark:border-gray-700">
            Mot de passe initial
        </h4>

        {{-- Champs du Mot de passe (obligatoires à la création) --}}
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" 
                          required autocomplete="new-password" />
            <x-input-error class="mt-2" :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" 
                          required autocomplete="new-password" />
            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
        </div>

        {{-- Boutons d'action --}}
        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
            <x-secondary-button type="button" onclick="closeModal()">
                {{ __('Annuler') }}
            </x-secondary-button>
            
            {{-- Le bouton de soumission appelle la fonction JS dans x-layouts.table --}}
            <x-primary-button type="button" onclick="submitCurrentForm()">
                {{ __('Créer l\'utilisateur') }}
            </x-primary-button>
        </div>
    </form>
</div>