<x-layouts.app>
 
    
        
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($users->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <p>Aucun utilisateur enregistré pour le moment.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                            <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-4 border-b text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Rôle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($users as $user)
                            <tr data-id="{{ $user->id }}" 
                                data-route="user" 
                                class="group cursor-pointer hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                                
                                {{-- Actions --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <button type="button"
                                                data-edit-url="{{ route('user.edit', $user->id) }}"
                                                
                                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.25 2.25 0 113.182 3.182L11.818 13H8v-3.818l8.773-8.773z" />
                                            </svg>
                                        </button>

                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    onclick=" return confirm('Supprimer définitivement cet utilisateur ?');"
                                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                                {{-- ID --}}
                                <td class="px-6 py-4 text-center font-mono text-sm text-gray-500">
                                    #{{ $user->id }}
                                </td>

                                {{-- Nom --}}
                                <td class="px-6 py-4 text-left">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ ucfirst($user->name) }}
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-6 py-4 text-left">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->email ?? '—' }}
                                    </div>
                                </td>

                                {{-- Rôle avec Badge --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                        {{ ucfirst($user->role ?? 'Membre') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif 
   
</x-layouts.app>