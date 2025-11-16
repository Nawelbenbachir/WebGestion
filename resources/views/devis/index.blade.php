<x-layouts.app>
<x-layouts.table createRoute="documents.create" createLabel="Ajouter un devis">

    {{-- Message succès --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Vérifie s’il y a des documents --}}
    @if($documents->isEmpty())
        <div class="alert alert-info">Aucun document enregistré pour le moment.</div>
    @else
        <table id="documents-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100">Code Devis</th>
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100">Type</th>
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100">Date</th>
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100">Client</th>
                    <th class="px-6 py-3 border-b text-end dark:text-gray-100">Total TTC (€)</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                @foreach($documents as $document)
                    <tr data-id="{{ $document->id }}" class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $document->code_document }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ ucfirst($document->type_document) }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $document->date_document }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $document->client_nom }}</td>
                        <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">{{ number_format($document->total_ttc, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal d’édition --}}
        <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">

                {{-- Bouton fermer --}}
                <button id="closeModalBtn"
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">
                    ✖
                </button>

                {{-- Contenu scrollable --}}
                <div id="devis-details" class="overflow-auto p-6 flex-1 space-y-4">
                    <!-- Le formulaire sera injecté ici -->
                </div>

                {{-- Boutons en bas --}}
                <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <x-secondary-button id="cancelModalBtn">Annuler</x-secondary-button>
                    <x-primary-button id="saveModalBtn" type="button">Enregistrer</x-primary-button>
                </div>
            </div>
        </div>

        <script>
function openModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('closeModalBtn').addEventListener('click', closeModal);
document.getElementById('cancelModalBtn').addEventListener('click', closeModal);
document.getElementById('saveModalBtn').addEventListener('click', () => {
    const form = document.querySelector('#devis-details form');
    if (form) form.requestSubmit ? form.requestSubmit() : form.submit();
});

document.addEventListener('DOMContentLoaded', () => {
    const deleteForm = document.getElementById('delete-form'); // Assure-toi que ce formulaire existe

    document.querySelectorAll('#documents-table tbody tr').forEach(row => {

        // Double-clic : ouvrir modal
        row.addEventListener('dblclick', () => {
            const docId = row.dataset.id;

            fetch(`/documents/${docId}/edit`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => {
                    if (!res.ok) throw new Error('Erreur ' + res.status);
                    return res.text();
                })
                .then(html => {
                    const container = document.getElementById('devis-details');
                    container.innerHTML = html;

                    // Tailwind classes
                    container.querySelectorAll('input, select, textarea').forEach(el => {
                        el.classList.add(
                            'w-full','rounded-md','border-gray-300','dark:border-gray-600',
                            'bg-white','dark:bg-gray-800','text-gray-900','dark:text-gray-100',
                            'shadow-sm','px-2','py-1'
                        );
                    });
                    container.querySelectorAll('label').forEach(lb => {
                        lb.classList.add('block','text-sm','font-medium','text-gray-700','dark:text-gray-300','mb-1');
                    });

                    openModal();
                })
                .catch(err => {
                    console.error('Erreur de chargement :', err);
                    alert('Impossible de charger le formulaire.');
                });
        });

        // Clic simple : sélection / suppression
        row.addEventListener('click', () => {
            if (!deleteForm) return;

            const id = row.dataset.id;
            const route = row.dataset.route || 'documents';

            deleteForm.setAttribute('action', `/${route}/${id}`);
            deleteForm.classList.remove('hidden');
        });

    });
});
</script>

    @endif
</x-layouts.table>
</x-layouts.app>
