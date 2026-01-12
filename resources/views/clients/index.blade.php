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

    @if($clients->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-gray-500 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
            <p class="text-lg">Aucun client enregistré.</p>
        </div>
    @else
        <div class="overflow-hidden">
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
                            class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    {{-- Édition --}}
                                    <button type="button"
                                            data-edit-url="{{ route('clients.edit', $client->id) }}"
                                            onclick="event.stopPropagation();"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all"
                                            title="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L11.818 13H8v-3.818l8.773-8.773z" />
                                        </svg>
                                    </button>

                                    {{-- Suppression --}}
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                onclick="event.stopPropagation(); return confirm('Supprimer définitivement le client {{ $client->societe }} ?');"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all"
                                                title="Supprimer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
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