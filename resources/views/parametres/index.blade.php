<x-layouts.app>
     <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>

    <div class="py-6" x-data="{ currentTab: 'societe' }">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                <div class="flex border-b border-gray-200 dark:border-gray-700">
    
    {{-- Onglet Société --}}
    <button @click="currentTab = 'societe'" 
            :class="{ 
                // CLASSE ACTIVE: En mode clair (bleu foncé)
                'border-b-2 border-indigo-500 text-indigo-600 font-semibold': currentTab === 'societe', 
                
                // AJUSTEMENT POUR LE MODE SOMBRE: Surcharger le texte en gris très clair et ajouter un fond subtil.
                'dark:text-gray-100 dark:bg-gray-700/50': currentTab === 'societe', 

                // CLASSE INACTIVE: (couleurs neutres pour les deux modes)
                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700/50': currentTab !== 'societe' 
            }"
            class="py-4 px-6 block appearance-none focus:outline-none transition duration-150 ease-in-out">
        Gestion des sociétés
    </button>
    
    {{-- Onglet Utilisateurs --}}
    <button @click="currentTab = 'utilisateur'" 
            :class="{ 
                // CLASSE ACTIVE: En mode clair (bleu foncé)
                'border-b-2 border-indigo-500 text-indigo-600 font-semibold': currentTab === 'utilisateur', 

                // AJUSTEMENT POUR LE MODE SOMBRE: Surcharger le texte en gris très clair et ajouter un fond subtil.
                'dark:text-gray-100 dark:bg-gray-700/50': currentTab === 'utilisateur', 

                // CLASSE INACTIVE: (couleurs neutres pour les deux modes)
                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700/50': currentTab !== 'utilisateur' 
            }"
            class="py-4 px-6 block appearance-none focus:outline-none transition duration-150 ease-in-out">
        Gestion des utilisateurs
    </button>
</div>

                {{-- Contenu des Onglets --}}
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    
                    {{-- Bloc 1: Paramètres Société --}}
                    <div x-show="currentTab === 'societe'">
                        @include('societe.index')
                    </div>

                    {{-- Bloc 2: Gestion des Utilisateurs --}}
                    <div x-show="currentTab === 'utilisateur'">
                        @include('user.index')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>