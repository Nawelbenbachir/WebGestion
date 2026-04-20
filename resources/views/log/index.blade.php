<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>
    <form method="GET" action="{{ route('log.index') }}">
        <select name="user_id" class="w-auto rounded border-gray-300 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm">
            <option value="">-- Tous les utilisateurs --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" 
                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    <button type="submit">Filtrer</button>
    </form>
    <x-layouts.table>
    @if($logs->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <p>Aucune connexion enregistrée.</p>
            </div>
        @else
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">User ID</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Nom Utilisateur</th>
                        <th class="px-6 py-4 border-b text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Date et heure de connexion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($logs as $log)
                        <tr>
                            <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $log->user_id }}</td>
                            <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $log->user?->name ?? 'Utilisateur supprimé' }}</td>
                            <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-layouts.table>
</x-layouts.app>