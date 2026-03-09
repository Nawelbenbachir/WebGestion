<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>
     @php
        $Etat = [
            'paye'    => 'Payée',
            'envoye'   => 'Envoyée',
            'brouillon'   => 'Brouillon'
            
        ];
        $modeStyles = [
            'paye'    => 'bg-green-100 text-green-800',
            'envoye'   => 'bg-blue-100 text-blue-800',
            'brouillon'   => 'bg-orange-100 text-amber-800',
            
        ];
    @endphp
      <div>
            <form action="{{ route('export.compta') }}" method="GET" class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-1">
                    <select name="mois" class="text-xs border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-purple-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="annee" class="text-xs border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-purple-500">
                        @foreach(range(date('Y')-2, date('Y')+1) as $a)
                            <option value="{{ $a }}" {{ date('Y') == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exporter
                </button>
            </form>
        </div>
    <x-layouts.table createRoute="documents.create" createLabel="Nouvelle Facture">
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
      
        @if($documents->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <p>Aucune facture enregistrée.</p>
            </div>
        @else
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total TTC</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Solde</th>
                         <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($documents as $document)
                        @php 
                            $hasAvoir = \App\Models\EnTeteDocument::where('facture_id', $document->id)->exists(); 
                        @endphp
                        <tr data-id="{{ $document->id }}" 
                            data-route="documents"
                            x-show="isMatch($el)"
                            class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                            
                            <td class="px-6 py-4 text-center whitespace-nowrap relative">
                                <div class="flex items-center justify-center space-x-1">
                                    
                                    {{--  PDF --}}
                                    <a href="{{ route('documents.pdf', $document->id) }}" 
                                    target="_blank"
                                    onclick="event.stopPropagation();"
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                    title="Télécharger PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 15L12 19L8 15M12 19V5" />
                                        </svg>
                                    </a>

                                    {{--  ÉDITION --}}
                                    <button type="button"
                                            data-edit-url="{{ route('documents.edit', $document->id) }}"
                                            onclick="event.stopPropagation();"
                                            class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                            title="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L11.818 13H8v-3.818l8.773-8.773z" />
                                        </svg>
                                    </button>

                                    {{--  RÈGLEMENT (Si non soldée) --}}
                                    @if($document->solde > 0)
                                        <button type="button"
                                                onclick="event.stopPropagation(); openModal('{{ route('reglements.create', ['document_id' => $document->id, 'client_id' => $document->client_id]) }}')"
                                                class="p-1.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-all"
                                                title="Enregistrer un règlement">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>
                                    @else
                                        <span class="p-1.5 text-emerald-500" title="Facture réglée">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                    @endif

                                    {{--  MENU PLUS ACTIONS --}}
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button @click.stop="open = !open" @click.outside="open = false" 
                                                type="button" 
                                                class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>

                                        <div x-show="open" 
                                            x-transition
                                            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 w-56 z-[100] bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden text-left">
                                            
                                            <div class="py-1">
                                                {{-- Avoir --}}
                                                @if(!$hasAvoir)
                                                    <form action="{{ route('documents.transformToAvoir', $document->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" onclick="return confirm('Générer un avoir ?');"
                                                                class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-gray-700">
                                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                            Générer un Avoir
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Dupliquer --}}
                                                <form action="{{ route('documents.duplicate', [$document->id, 'F']) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Dupliquer cette facture ?');"
                                                            class="w-full flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                                                        Dupliquer la facture
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $document->code_document }}
                            </td>

                            <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">
                                {{ $document->client->societe}}
                            </td>

                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ number_format($document->total_ttc, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ number_format($document->solde, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                               <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $modeStyles[$document->statut] ?? 'bg-gray-100' }}">
                                    {{ $Etat[$document->statut] ?? $document->statut }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-layouts.table>
</x-layouts.app>