<div id="create-form-container" class="p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
    
    {{-- En-tête avec Icône --}}
    <div class="flex items-center gap-3 mb-6 border-b pb-4 dark:border-gray-700">
        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold">
            Création d'un <span class="text-green-600 dark:text-green-400">nouvel utilisateur</span>
        </h3>
    </div>

    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        
        {{-- Section 1 : Informations de compte --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="md:col-span-1">
                <x-input-label for="name" :value="__('Nom complet')" class="font-semibold" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full shadow-sm" 
                             :value="old('name')" required autofocus />
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div class="md:col-span-1">
                <x-input-label for="email" :value="__('Adresse Email')" class="font-semibold" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full shadow-sm" 
                             :value="old('email')" required />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="role" :value="__('Rôle Privilège')" class="font-semibold" />
                <select id="role" name="role" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100 focus:ring-green-500 focus:border-green-500">
                    <option value="user" @selected(old('role') == 'user')>User</option>
                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                </select>
                <x-input-error class="mt-1" :messages="$errors->get('role')" />
            </div>
        </div>

        {{-- Section 2 : Sécurité (Séparateur) --}}
        <div class="relative py-8">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t dark:border-gray-700"></div>
            </div>
            <div class="relative flex justify-start">
                <span class="pr-3 bg-white dark:bg-gray-800 text-sm font-semibold text-gray-500 uppercase tracking-wider">Sécurité & Accès</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <x-input-label for="password" :value="__('Mot de passe')" class="font-semibold" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full shadow-sm" required placeholder="••••••••" />
                <x-input-error class="mt-1" :messages="$errors->get('password')" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmation')" class="font-semibold" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full shadow-sm" required placeholder="••••••••" />
                <x-input-error class="mt-1" :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        {{-- Barre d'actions --}}
        <div class="flex items-center justify-end gap-3 pt-6 border-t dark:border-gray-700">
            <x-secondary-button type="button" onclick="closeModal()" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Annuler') }}
            </x-secondary-button>
            
            <button type="button" onclick="submitCurrentForm()" 
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Créer l\'utilisateur') }}
            </button>
        </div>
    </form>
</div>