<x-layouts.app>
     <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
{{ __('Tableau de bord') }}
</h2>
</x-slot>

<div class="py-12">
    <div class=" mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg p-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Chiffre d'Affaires (Mois)</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                    {{ number_format($monthly_ca ?? 0, 2, ',', ' ') }} </p>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg p-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Devis en attente</p>
                <p class="text-3xl font-bold text-amber-500 mt-1">
                    {{ $pending_quotes_count }} </p>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg p-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Impayés</p>
                <p class="text-3xl font-bold text-red-500 mt-1">
                    {{ number_format($unpaid_total ?? 0, 2, ',', ' ') }} </p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg p-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nouveaux Clients (Mois)</p>
                <p class="text-3xl font-bold  text-gray-500 dark:text-gray-400 mt-1">
                    {{ $new_clients_count }} </p>
            </div>

        </div>
        <!-- Grille des Raccourcis (Shortcuts Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">

           
             <a href="{{ route('clients.index') }}" class="block px-5 py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Clients
                    </span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2a3 3 0 00-5.356-1.857M9 20V17a4 4 0 014-4h4c1.11 0 2.052.288 2.846.852M9 20v-2a3 3 0 015.644-1.857M12 10a5 5 0 110-10 5 5 0 010 10z" />
                    </svg>
                </div>
                
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Gérer les fiches clients.
                </p>
            </a>

           
            <a href="{{ route('produits.index') }}" class="block px-5 py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Produits
                    </span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
               
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Gérer les fiches produits.
                </p>
            </a>
            
            <a href="{{ route('documents.index', ['type' => 'facture']) }}" class="block px-5 py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                   
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Factures
                    </span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Visualiser et créer les factures.
                </p>
            </a>

            
             <a href="{{ route('documents.index', ['type' => 'devis']) }}" class="block px-5 py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Devis
                    </span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Visualiser et créer les devis.
                </p>
            </a>

            
            <a href="{{ route('documents.index', ['type' => 'avoir']) }}" class="block px-5 py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Avoirs
                    </span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-7 4h7" />
                    </svg>
                </div>
               
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Visualiser et créer les avoirs.
                </p>
            </a>
            
            
            <a href="{{ route('reglements.index') }}" class="block px-5 py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Règlements
                    </span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-500 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 10v-1m-4-2H5m14 0h-4M7 12h10m-2 2v2m-4-2v2m-6 4h16a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Enregistrement et suivi des paiements reçus.
                </p>
            </a>

            <!-- Raccourci 7: Commandes -->
            <!-- <a href="{{ route('documents.index', ['type' => 'commande']) }}" class="block p-5 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                  
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Commandes
                    </span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Gestion et suivi des bons de commande.
                </p>
            </a> -->

           
            {{-- Raccourci Paramètres --}}
    @if(Auth::user()->hasParametresAccess())
        <a href="{{ route('parametres.index') }}" class="block px-5 py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                
                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Paramètres
                </span>
                
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Configuration de l'entreprise et des utilisateurs.
            </p>
        </a>
    @endif
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-10">
        
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Dernières Factures Créées
        </h3>
        
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Code</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Montant TTC</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            {{-- Boucle sur les factures injectées par le View Composer --}}
                            @forelse ($latestInvoices as $invoice)
                                <tr class="hover:bg-gray-700 transition duration-150">
                                    
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">
                                        <a href="{{ route('documents.show', $invoice->id) }}" class="text-indigo-400 hover:text-indigo-300">
                                            {{ $invoice->code_document }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">
                                        {{ $invoice->client->nom ?? 'N/A' }} 
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">
                                        {{ date('d/m/Y', strtotime($invoice->date_document)) }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300 text-right font-semibold">
                                        {{ number_format($invoice->total_ttc ?? 0, 2, ',', ' ') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($invoice->statut == 'paye') bg-green-800 text-green-200
                                            @elseif($invoice->statut == 'impaye') bg-red-800 text-red-200
                                            @else bg-yellow-800 text-gray-400
                                            @endif">
                                            {{ Str::ucfirst($invoice->statut) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-400">
                                        Aucune facture récente trouvée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Alertes et Rappels
                </h3>
                </div>

        </div>
       
        
    </div>
</div>


</x-layouts.app>