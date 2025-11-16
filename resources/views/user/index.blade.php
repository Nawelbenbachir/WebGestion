<x-layouts.app>
<x-layouts.table createRoute="user.create" createLabel="Ajouter un utilisateur">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($users->isEmpty())
        <div class="alert alert-info">Aucun utilisateur enregistré pour le moment.</div>
    @else
        <table id="users-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center text-gray-900 dark:text-gray-100">ID</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center text-gray-900 dark:text-gray-100">Email</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center text-gray-900 dark:text-gray-100">Nom</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                @foreach ($users as $user)
                    <tr data-id="{{ $user->id }}" data-route="users" class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900">
                        <td class="px-6 py-4 border-b border-r border-gray-300 dark:border-gray-700">{{ $user->id }}</td>
                        <td class="px-6 py-4 border-b border-r border-gray-300 dark:border-gray-700">{{ $user->email ?? '—' }}</td>
                        <td class="px-6 py-4 border-b border-r border-gray-300 dark:border-gray-700">{{ ucfirst($user->name) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('#users-table tbody tr').forEach(row => {
                    row.addEventListener('click', () => {
                        const userId = row.dataset.id;
                        window.location.href = `/users/${userId}/edit`;
                    });
                });
            });
        </script>
    @endif
</x-layouts.table>
</x-layouts.app>