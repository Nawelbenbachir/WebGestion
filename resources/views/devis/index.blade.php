<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>

    
    <x-layouts.table createRoute="devis.create" createLabel="Nouveau Devis">
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($documents->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <p>Aucun document enregistré.</p>
            </div>
        @else
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase">Actions</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase">Total TTC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($documents as $document)
                        @php 
                            $isTransformed = \App\Models\EnTeteDocument::where('devis_id', $document->id)->exists(); 
                        @endphp
                        
                       
                        <tr data-id="{{ $document->id }}" 
                            data-route="devis"
                            class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                            
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    
                                    {{-- Bouton PDF --}}
                                    <a href="{{ route('documents.pdf', $document->id) }}" 
                                    target="_blank"
                                    onclick="event.stopPropagation();"
                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all"
                                    title="Télécharger PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 15L12 19L8 15M12 19V5" />
                                        </svg>
                                    </a>
                                    @if(!$isTransformed)
                                        <form action="{{ route('documents.transform', $document->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="event.stopPropagation(); return confirm('Transformer ce devis en facture ?');"
                                                    class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all"
                                                    title="Transformer en facture">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-emerald-500" title="Déjà transformé">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </span>
                                    @endif
                                    {{-- Bouton Dupliquer (Copie Devis vers Devis) --}}
                                    <form action="{{ route('documents.duplicate', [$document->id, 'D']) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                onclick="event.stopPropagation(); return confirm('Dupliquer ce devis ?');"
                                                class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all"
                                                title="Dupliquer le devis">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                            </svg>
                                        </button>
                                    </form>
                                    <button type="button"
                                            data-edit-url="{{ route('devis.edit', $document->id) }}"
                                            onclick="event.stopPropagation();"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L11.818 13H8v-3.818l8.773-8.773z" />
                                        </svg>
                                    </button>

                                    <form action="{{ route('devis.destroy', $document->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                onclick="event.stopPropagation(); return confirm('Supprimer définitivement ce document ?');"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $document->code_document }}
                            </td>
                            <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">
                                {{ $document->client->societe}}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ number_format($document->total_ttc, 2, ',', ' ') }} €
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-layouts.table>
</x-layouts.app>