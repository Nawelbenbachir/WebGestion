<div id="create-form-container" class="p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
    
    {{-- En-tête avec Icône --}}
    <div class="flex items-center gap-3 mb-6 border-b pb-4 dark:border-gray-700">
        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold">
            Modification de l'utilisateur : <span class="text-indigo-600 dark:text-indigo-400">{{ $user->name }}</span>
        </h3>
    </div>

    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT') 
        
        {{-- Section 1 : Informations Générales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <x-input-label for="name" :value="__('Nom complet')" class="font-semibold" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full shadow-sm" 
                             :value="old('name', $user->name)" required />
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Adresse Email')" class="font-semibold" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full shadow-sm" 
                             :value="old('email', $user->email)" required />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="role" :value="__('Rôle Privilège')" class="font-semibold" />
                <select id="role" name="role" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="user" @selected(old('role', $user->role) == 'user')>User</option>
                    <option value="admin" @selected(old('role', $user->role) == 'admin')>Admin</option>
                </select>
            </div>
        </div>

        {{-- Section 2 : Sécurité (Séparateur stylisé) --}}
        <div class="relative py-8">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t dark:border-gray-700"></div>
            </div>
            <div class="relative flex justify-start">
                <span class="pr-3 bg-white dark:bg-gray-800 text-sm font-semibold text-gray-500 uppercase tracking-wider">Sécurité</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <x-input-label for="password" :value="__('Nouveau Mot de passe')" class="font-semibold" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full shadow-sm" placeholder="••••••••" />
                <p class="mt-1 text-xs text-gray-500 italic text-right">Laissez vide pour conserver l'actuel</p>
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmation')" class="font-semibold" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full shadow-sm" placeholder="••••••••" />
            </div>
        </div>

        {{-- Barre d'actions fixe en bas de modal --}}
        <div class="flex items-center justify-end gap-3 pt-6 border-t dark:border-gray-700">
            <button type="button" onclick="closeModal()" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">
                {{ __('Annuler') }}
            </button>
            
            <button type="button" onclick="submitCurrentForm()" 
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ __('Enregistrer les modifications') }}
            </button>
        </div>
    </form>
</div>