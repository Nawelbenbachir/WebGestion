<x-layouts.app>
<x-slot name="navigation">
<x-navigation></x-navigation>
</x-slot>

<x-layouts.table createRoute="clients.create" createLabel="Ajouter un client">
    
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm transition-all animate-fade-in">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded shadow-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <ul class="list-none">
                    @foreach ($errors->all() as $error)
                        <li class="font-medium text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    @if($clients->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-gray-500 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
            <p class="text-lg">Aucun client enregistré.</p>
        </div>
    @else
        <div >
            <table id="clients-table" class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Société / Nom</th>
                        <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Règlement</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @foreach ($clients as $client)
                        <tr data-id="{{ $client->id }}" 
                            data-route="clients" 
                            x-show="isMatch($el)"
                            class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-center relative">
                                <div class="flex items-center justify-center space-x-1">
                                    
                                    {{-- Bouton Édition (Action principale, même style que Facture) --}}
                                    <button type="button" 
                                            data-edit-url="{{ route('clients.edit', $client->id) }}" 
                                            onclick="event.stopPropagation();" 
                                            class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all"
                                            title="Modifier le client">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L11.818 13H8v-3.818l8.773-8.773z" />
                                        </svg>
                                    </button>

                                    {{-- Menu Plus (Actions secondaires) --}}
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button @click.stop="open = !open" @click.outside="open = false" 
                                                type="button" 
                                                class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>

                                        {{-- Dropdown aligné sur le style Facture/Avoir --}}
                                        <div x-show="open" 
                                            x-transition:enter="transition ease-out duration-100" 
                                            x-transition:enter-start="transform opacity-0 scale-95" 
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 w-52 z-[100] bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden text-left">
                                            
                                            <div class="py-1">
                                                <a onclick="event.stopPropagation(); openModal('{{ route('documents.create', ['client_id' => $client->id, 'type' => 'devis']) }}')"
                                                    class="flex items-center px-4 py-2 text-sm text-amber-600 hover:bg-amber-50 dark:hover:bg-blue-900/20 cursor-pointer transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-3 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                        </svg>
                                                        Nouveau devis
                                                </a>

                                                <a onclick="event.stopPropagation(); openModal('{{ route('documents.create', ['client_id' => $client->id, 'type' => 'facture']) }}')" 
                                                    class="flex items-center px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 dark:hover:bg-emerald-900/20 cursor-pointer transition-colors">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                        Nouvelle facture
                                                </a>

                                                <a onclick="event.stopPropagation(); openModal('{{ route('reglements.create', ['client_id' => $client->id]) }}')" 
                                                class="flex items-center px-4 py-2 text-sm text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-900/20 cursor-pointer transition-colors">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    Règlement
                                                </a>

                                                {{-- Séparateur --}}
                                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                                {{-- Suppression (Intégrée au menu pour épurer la ligne) --}}
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Supprimer définitivement ce client ?');" 
                                                            class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $client->code_cli }}
                            </td>

                            <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">
                                {{ $client->societe }}
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-md {{ $client->type === 'professionnel' ? 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                    {{ ucfirst($client->type) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-left text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $client->telephone ?? '—' }}</span>
                                    <span class="text-xs opacity-75">{{ $client->email ?? '—' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-left text-sm text-gray-600 dark:text-gray-400">
                                <div class="truncate max-w-xs" title="{{ $client->adresse1 }} {{ $client->ville }}">
                                    {{ $client->ville ?? '—' }} <span class="text-xs opacity-50">({{ $client->code_postal }})</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ match($client->reglement ?? '') {
                                        'virement' => 'Virement',
                                        'cheque' => 'Chèque',
                                        'especes' => 'Espèces',
                                        default => '—'
                                    } }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-layouts.table>


</x-layouts.app>