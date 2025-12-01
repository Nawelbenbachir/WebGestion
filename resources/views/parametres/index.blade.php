<x-layouts.app>
     <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <svg class="w-6 h-6 inline-block me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            **Paramètres de l'Application**
        </h2>
    </x-slot>

    {{-- Conteneur Alpine.js pour gérer l'état de l'onglet --}}
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