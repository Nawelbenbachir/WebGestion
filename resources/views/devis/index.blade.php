<x-layouts.app>
<x-layouts.table createRoute="documents.create" createLabel="Ajouter un devis">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($documents->isEmpty())
        <div class="alert alert-info">Aucun document enregistré pour le moment.</div>
    @else
        <table id="documents-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b border-r text-center dark:text-gray-100">Code Devis</th>
                    <th class="px-6 py-3 border-b border-r text-center dark:text-gray-100">Type</th>
                    <th class="px-6 py-3 border-b border-r text-center dark:text-gray-100">Date</th>
                    <th class="px-6 py-3 border-b border-r text-center dark:text-gray-100">Client</th>
                    <th class="px-6 py-3 border-b text-end dark:text-gray-100">Total TTC (€)</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                @foreach($documents as $document)
                    <tr data-id="{{ $document->id }}" class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 border-b border-r">{{ $document->code_document }}</td>
                        <td class="px-6 py-4 border-b border-r">{{ ucfirst($document->type_document) }}</td>
                        <td class="px-6 py-4 border-b border-r">{{ $document->date_document }}</td>
                        <td class="px-6 py-4 border-b border-r">{{ $document->client_nom }}</td>
                        <td class="px-6 py-4 border-b text-end">{{ number_format($document->total_ttc, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Modal d’édition devis --}}
        <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">

                <!-- Bouton fermer -->
                <button onclick="closeModal()" 
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">
                    ✖
                </button>

                <!-- Contenu scrollable -->
                <div id="document-details" class="overflow-auto p-6 flex-1 space-y-4">
                    <!-- Formulaire injecté dynamiquement ici -->
                </div>

                <!-- Boutons en bas
                <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <x-secondary-button as="a" href="#" onclick="closeModal()">⬅️ Annuler</x-secondary-button>
                    <x-primary-button type="submit" form="produit-form">💾 Enregistrer</x-primary-button>
                </div> -->
            </div>
        </div>
        <script>
             function openModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
            document.addEventListener('DOMContentLoaded', function() {
                const deleteForm = document.getElementById('delete-form');
                if (!deleteForm) return;
                document.querySelectorAll('#documents-table tbody tr').forEach(row => {
                    row.addEventListener('dblclick', () => {
                        
                        const docId = row.dataset.id;
                        window.location.href = `/documents/${docId}/edit`;
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
    @endif
</x-layouts.table>
</x-layouts.app>