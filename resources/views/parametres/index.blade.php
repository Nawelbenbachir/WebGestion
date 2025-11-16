<x-layouts.app>
<x-layouts.table createRoute="parametres.create" createLabel="Ajouter une société">

    <table id="parametres-table" class="min-w-full w-full border border-gray-200 dark:border-gray-700">

        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                @foreach ([
                    'Code', 'Siret', 'Nom', 'Téléphone', 'Portable 1', 'Portable 2', 'Email',
                    'Adresse', 'Adresse 2', 'Complément', 'Code Postal', 'Ville', 'IBAN'
                ] as $col)
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">
                        {{ $col }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            @foreach ($societes as $societe)
                <tr data-id="{{ $societe->id }}" 
                    data-route="parametres"
                    class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900 transition">

                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->code_societe }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->siret ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ ucfirst($societe->nom_societe) }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->telephone ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->portable1 ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->portable2 ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->email ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->adresse1 ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->adresse2 ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->complement_adresse ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->code_postal ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-center dark:border-gray-700">{{ $societe->ville ?? '—' }}</td>
                    <td class="px-6 py-4 border-b text-end dark:border-gray-700">{{ $societe->iban ?? '—' }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
    </x-layouts.table>
@push ('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const deleteForm = document.getElementById('delete-form');
    if (!deleteForm) return;

    document.querySelectorAll('tr[data-id]').forEach(row => {
        row.addEventListener('click', () => {
            const id = row.dataset.id;
            const route = row.dataset.route;

            // Met à jour l’URL de suppression
            deleteForm.setAttribute('action', `/${route}/${id}`);

            // Affiche le bouton supprimer
            deleteForm.classList.remove('hidden');
        });
    });
});
</script>
@endpush

</x-layouts.app>
