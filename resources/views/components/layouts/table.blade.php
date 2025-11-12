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
                    as="a"
                    href="{{ route($createRoute) }}"
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

</div>

{{-- Script pour gérer la sélection et la suppression --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const deleteForm = document.getElementById('delete-form');

    document.querySelectorAll('tr[data-id]').forEach(row => {
        row.addEventListener('click', () => {
            const itemId = row.dataset.id;
            const baseRoute = row.dataset.route;

            // Met à jour l’action du formulaire
            deleteForm.setAttribute('action', `/${baseRoute}/${itemId}`);

            // Affiche le bouton supprimer
            deleteForm.classList.remove('hidden');
        });
    });
});
</script>
@endpush
