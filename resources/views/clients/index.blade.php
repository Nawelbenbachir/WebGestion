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

            <tbody class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($clients as $client)
                    <tr data-id="{{ $client->id }}" data-route="clients" class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900">
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->societe }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ ucfirst($client->type) }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->telephone ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->email ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->adresse1 ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->code_postal ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->ville ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">
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

        <!-- Modal -->
        <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">
                
                <!-- Bouton fermer -->
                <button id="closeModalBtn" 
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">
                    ✖
                </button>

                <!-- Contenu -->
                <div id="client-details" class="overflow-auto p-6 flex-1 space-y-4">
                    <!-- Le formulaire sera injecté ici -->
                </div>

                <!-- Boutons en bas -->
                <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <x-secondary-button id="cancelModalBtn">Annuler</x-secondary-button>
                    <x-primary-button id="saveModalBtn" type="button"> Enregistrer</x-primary-button>
                </div>
            </div>
        </div>

        <script>
        // Helpers modal
        function openModal() {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Gestion des boutons
        document.getElementById('closeModalBtn').addEventListener('click', closeModal);
        document.getElementById('cancelModalBtn').addEventListener('click', closeModal);
        document.getElementById('saveModalBtn').addEventListener('click', () => {
            const form = document.getElementById('client-form');
            if (form) {
                form.requestSubmit ? form.requestSubmit() : form.submit();
            }
        });

        // Chargement dynamique du formulaire
        document.addEventListener('DOMContentLoaded', () => {
            const deleteForm = document.getElementById('delete-form');
             if (!deleteForm) return;
            document.querySelectorAll('tr[data-id]').forEach(row => {
                row.addEventListener('dblclick', () => {
                    
                    const clientId = row.dataset.id;
                    
                    fetch(`/clients/${clientId}/edit`)
                        .then(res => {
                            if (!res.ok) throw new Error('HTTP error ' + res.status);
                            return res.text();
                        })
                        .then(html => {
                            const container = document.getElementById('client-details');
                            container.innerHTML = html;

                            // Adaptation visuelle dark mode
                            container.querySelectorAll('input, select, textarea').forEach(el => {
                                if (!el.classList.contains('rounded-md')) {
                                    el.classList.add('w-full','rounded-md','border-gray-300','dark:border-gray-600',
                                                    'bg-white','dark:bg-gray-800','text-gray-900','dark:text-gray-100',
                                                    'shadow-sm','px-2','py-1');
                                }
                            });
                            container.querySelectorAll('label').forEach(lb => {
                                lb.classList.add('block','text-sm','font-medium','text-gray-700','dark:text-gray-300','mb-1');
                            });

                            openModal();
                        })
                        .catch(err => {
                            console.error('Erreur de chargement :', err);
                            alert('Erreur lors du chargement du formulaire.');
                        });
                });
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
    </x-layouts.table>
</x-layouts.app>
