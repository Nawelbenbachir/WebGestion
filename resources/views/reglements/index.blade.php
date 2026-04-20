<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>
    @php
        $modeLabels = [
            'carte'    => 'Carte bancaire',
            'espece'   => 'Espèces',
            'cheque'   => 'Chèque',
            'virement' => 'Virement'
        ];
        $modeStyles = [
            'carte'    => 'bg-blue-100 text-blue-800',
            'espece'   => 'bg-green-100 text-green-800',
            'cheque'   => 'bg-orange-100 text-amber-800',
            'virement' => 'bg-purple-100 text-purple-800'
        ];
    @endphp
     <div>
            <form action="{{ route('export.reglements') }}" method="GET" class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-1">
                    <select name="mois"  class="text-xs border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-purple-500">
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
                    Exporter les règlements
                </button>
            </form>
    </div>
    <x-layouts.table createRoute="reglements.create" createLabel="Nouveau Règlement">
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
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

        @if($reglements->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <p>Aucun règlement enregistré.</p>
            </div>
        @else
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase">Numéro</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Code document</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase">Total TTC</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase">Mode de règlement</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase">Commentaire</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase">Exporté</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($reglements as $reglement)
                        <tr data-id="{{ $reglement->id }}" 
                            x-show="isMatch($el)"
                            class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                            <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                <form action="{{ route('reglements.destroy', $reglement->id) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ce règlement ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 text-gray-400 hover:text-rose-500 rounded-md transition-all group"
                                            title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                {{ $reglement->reference}}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $reglement->numero_reglement }}
                            </td>
                            <td class="px-6 py-4 text-left font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $reglement->date_reglement }}
                            </td>
                            <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">
                                {{ $reglement->document->first()->client->societe}}
                            </td>
                             <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">
                                {{ $reglement->document->pluck('code_document')->join(', ')}}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ number_format($reglement->montant, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $modeStyles[$reglement->mode_reglement] ?? 'bg-gray-100' }}">
                                    {{ $modeLabels[$reglement->mode_reglement] ?? $reglement->mode_reglement }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">
                                {{ $reglement->commentaire}}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($reglement->exporte)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </td>
                        </tr>
                   @endforeach
                </tbody>
            </table>
        @endif
    </x-layouts.table>
</x-layouts.app>