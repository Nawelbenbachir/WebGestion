<x-layouts.app>
       
    @if($societes->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-gray-500">
            <p>Aucune société enregistrée.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase">Actions</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase">Code</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Nom / SIRET</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase">Localisation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($societes as $soc)
                        <tr data-id="{{ $soc->id }}" 
                            data-route="societes"
                            x-show="isMatch($el)"
                            class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                            
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">

                                    {{-- Édition : propriétaire ET admin --}}
                                    @if($isProprietaire || $isAdmin)
                                        <button type="button"
                                                data-edit-url="{{ route('societes.edit', $soc->id) }}"
                                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all"
                                                title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L11.818 13H8v-3.818l8.773-8.773z" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Suppression : propriétaire uniquement --}}
                                    @if($isProprietaire)
                                        <form action="{{ route('societes.destroy', $soc->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Supprimer définitivement cette société ?');"
                                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all"
                                                    title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Badge propriétaire --}}
                                    @if($soc->proprietaire_id === auth()->id())
                                        <span class="px-1.5 py-0.5 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded">
                                            Propriétaire
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $soc->code_societe }}
                            </td>

                            <td class="px-6 py-4 text-left">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ strtoupper($soc->nom_societe) }}</div>
                                <div class="text-xs text-gray-500">SIRET: {{ $soc->siret ?? '—' }}</div>
                            </td>

                            <td class="px-6 py-4 text-left">
                                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $soc->email ?? '—' }}</div>
                                <div class="text-xs text-gray-500">{{ $soc->telephone ?? '—' }}</div>
                            </td>

                            <td class="px-6 py-4 text-left">
                                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $soc->ville ?? '—' }}</div>
                                <div class="text-xs text-gray-500">{{ $soc->code_postal ?? '—' }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
</x-layouts.app>