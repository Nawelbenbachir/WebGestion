@extends('layouts.app')
@section('content')
<div class="w-full p-6">

    {{--  En-tête : titre + boutons --}}
    <div class="flex flex-col items-start mb-6 gap-3">

        @if(isset($createRoute))
            {{-- Bouton Ajouter --}}
            <a href="{{ route($createRoute) }}" 
               class=" bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-auto text-center">
               ➕ {{ $createLabel ?? 'Ajouter' }}
            </a>

            {{-- Bouton Supprimer --}}
            <form id="delete-form" method="POST" action="#" 
                  class="hidden w-full sm:w-auto"
                  onsubmit="return confirm('Supprimer cet élément ?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg w-auto text-center">
                    🗑️ Supprimer
                </button>
            </form>
        @endif
    </div>

    {{--  Zone du tableau --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto w-full">
        @yield('table')
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const deleteForm = document.getElementById('delete-form');

    document.querySelectorAll('tr[data-id]').forEach(row => {
        row.addEventListener('click', () => {
            const clientId = row.dataset.id;
            const baseRoute = row.dataset.route;

            // Met à jour l'action du formulaire suppression
            deleteForm.setAttribute('action', `/${baseRoute}/${clientId}`);

            // Affiche le formulaire si caché
            deleteForm.classList.remove('hidden');
        });
    });
});
</script>

@endsection
