<x-layouts.table createRoute="user.create" createLabel="Ajouter un utilisateur" hideModal="true">
        @if($users->isEmpty())
            <div class="alert alert-info p-6 text-gray-900 dark:text-gray-100">Aucun utilisateur enregistré pour le moment.</div>
        @else
        <div>
            <table id="users-table" class="min-w-full w-full border border-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 text-center text-gray-900 dark:text-gray-100"></th>
                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 text-center text-gray-900 dark:text-gray-100">ID</th>
                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 text-center text-gray-900 dark:text-gray-100">Nom</th>
                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 text-center text-gray-900 dark:text-gray-100">Email</th>
                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 text-center text-gray-900 dark:text-gray-100">Rôle</th> {{-- Ajout potentiel pour le rôle --}}
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
                    @foreach ($users as $user)
                        
                        <tr data-id="{{ $user->id }}" data-route="users" class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900">
                            
                            <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">
                                <x-action-buttons 
                                    :id="$user->id"
                                    :edit-url="route('user.edit', $user->id)"
                                    :delete-route="route('user.destroy', $user->id)"
                                    delete-label=" l'utilisateur {{ $user->name }}"
                                />
                            </td>

                            <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $user->id }}</td>
                            <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ ucfirst($user->name) }}</td>
                            <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $user->email ?? '—' }}</td>
                            
                            {{-- Afficher le rôle si l'attribut existe --}}
                            <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">
                                {{ $user->role ?? 'Non défini' }} 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

</x-layouts.table>
