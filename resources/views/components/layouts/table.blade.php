@props([
    'createRoute' => null,
    'createLabel' => 'Ajouter',
])

<div class="w-full p-6">

    {{-- En-tête : titre + boutons --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        @if($createRoute)
            <div class="flex gap-3">
                {{-- Bouton Ajouter --}}
                <x-primary-button
                    type="button"
                    onclick="openCreateModal('{{ route($createRoute) }}')"
                    class="bg-blue-600 hover:bg-blue-700 text-white"
                >
                    {{ $createLabel }}
                </x-primary-button>

                {{-- Bouton Supprimer --}}
                <form id="delete-form" method="POST" action="#"
                      class="hidden"
                      onsubmit="return confirm('Supprimer cet élément ?')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white"
                    >
                        Supprimer
                    </x-danger-button>
                </form>
            </div>
        @endif
    </div>

    {{-- Zone du tableau --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto w-full">
        {{ $slot }}
    </div>

    {{-- Modal de création --}}
    <div id="createModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">

            {{-- Bouton fermer --}}
            <button onclick="closeCreateModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">
                ✖
            </button>

            {{-- Contenu scrollable --}}
            <div id="create-form-container" class="overflow-auto p-6 flex-1 space-y-4"></div>

            {{-- Boutons bas --}}
            <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <x-secondary-button type="button" onclick="closeCreateModal()">⬅️ Annuler</x-secondary-button>
                <x-primary-button type="submit" form="client-form">💾 Enregistrer</x-primary-button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
@push('scripts')
<script>
function openCreateModal(url) {
    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Erreur HTTP ' + res.status);
            return res.text();
        })
        .then(html => {
            const container = document.getElementById('create-form-container');
            container.innerHTML = html;

            // Appliquer les styles Tailwind si nécessaire
            container.querySelectorAll('input, select, textarea').forEach(el => {
                if (!el.classList.contains('rounded-md')) {
                    el.classList.add('w-full','rounded-md','border-gray-300','dark:border-gray-600','bg-white','dark:bg-gray-800','text-gray-900','dark:text-gray-100','shadow-sm','px-2','py-1');
                }
            });
            container.querySelectorAll('label').forEach(lb => {
                if (!lb.classList.contains('block')) {
                    lb.classList.add('block','text-sm','font-medium','text-gray-700','dark:text-gray-300','mb-1');
                }
            });

            // Affiche le modal
            document.getElementById('createModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        })
        .catch(err => {
            console.error('Erreur de chargement du formulaire de création :', err);
            alert('Erreur lors du chargement du formulaire.');
        });
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}
</script>
@endpush
