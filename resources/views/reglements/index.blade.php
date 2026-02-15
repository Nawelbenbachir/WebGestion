<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>

    
    <x-layouts.table createRoute="reglements.create" createLabel="Nouveau Règlement">
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
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
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-4 border-b text-right text-xs font-bold text-gray-500 uppercase">Total TTC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($reglements as $reglement)
                        <tr data-id="{{ $reglement->id }}" 
                            
                            class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                            
                       
                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $reglement->numero_reglement }}
                            </td>
                            <td class="px-6 py-4 text-left font-medium text-gray-900 dark:text-white">
                                {{ $reglement->document->code_document}}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ number_format($reglement->montant, 2, ',', ' ') }} €
                            </td>
                        </tr>
                   @endforeach
                </tbody>
            </table>
        @endif
    </x-layouts.table>
</x-layouts.app>