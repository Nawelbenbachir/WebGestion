<x-layouts.app>
    <x-layouts.table createRoute="clients.create" createLabel="Ajouter un client">
        <table id="clients-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Société</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Type</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Téléphone</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Email</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Adresse</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Code Postal</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Ville</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Mode de règlement</th>
                </tr>
            </thead>

            {{-- Corps du tableau --}}
            <tbody class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($clients as $client)
                    <tr data-id="{{ $client->id }}" data-route="clients"
                        class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900">
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $client->societe }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ ucfirst($client->type) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $client->telephone ?? '—' }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $client->email ?? '—' }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $client->adresse1 ?? '—' }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $client->code_postal ?? '—' }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $client->ville ?? '—' }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">
                            {{ match($client->reglement ?? '') {
                                'virement' => 'Virement',
                                'cheque' => 'Chèque',
                                'especes' => 'Espèces',
                                default => '—'
                            } }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

            
  <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">

        <!-- Bouton fermer -->
        <button onclick="closeModal()" 
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">
            ✖
        </button>

        <!-- Contenu scrollable -->
        <div id="client-details" class="overflow-auto p-6 flex-1 space-y-4">
            <!-- Ton formulaire injecté ici -->
        </div>

        <!-- Boutons en bas, toujours visibles -->
        <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <x-secondary-button as="a" href="#" onclick="closeModal()">⬅️ Annuler</x-secondary-button>
            <x-primary-button type="submit" form="client-form">💾 Enregistrer</x-primary-button>
        </div>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>





    <script>
   function openModal() {
    document.getElementById('editModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Chargement du formulaire
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('tr[data-id]').forEach(row => {
        row.addEventListener('click', () => {
            const clientId = row.dataset.id;
            fetch(`/clients/${clientId}/edit`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('client-details').innerHTML = html;
                    openModal();
                })
                .catch(err => console.error('Erreur de chargement :', err));
        });
    });
});

    </script>

        <!-- {{-- Zone du formulaire d’édition (injectée dynamiquement) --}}
        <div id="client-details" class="bg-gray-50 dark:bg-gray-900 shadow-inner rounded-lg p-4 mt-4 hidden"></div>
                    
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('tr[data-id]').forEach(row => {
                row.addEventListener('click', () => { //clic
                    const clientId = row.dataset.id;
                    fetch(`/clients/${clientId}/edit`)
                        .then(res => res.text())
                        .then(html => {
                            const detailsDiv = document.getElementById('client-details');
                            detailsDiv.innerHTML = html;
                            detailsDiv.classList.remove('hidden');
                            detailsDiv.scrollIntoView({ behavior: 'smooth' });
                        })
                        .catch(err => console.error('Erreur de chargement :', err));
                });
            });
        });
        </script>
        @endpush -->
    </x-layouts.table>
</x-layouts.app>
